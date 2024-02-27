<?php

declare(strict_types=1);

namespace App\Admin\Services\ReportCompiler\Factory;

use App\Admin\Models\Booking\Booking;
use App\Admin\Models\Order\Guest;
use App\Admin\Models\Supplier\Supplier;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as Query;
use Illuminate\Support\Collection;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Shared\Contracts\Service\TranslatorInterface;
use Sdk\Shared\Enum\ServiceTypeEnum;

class ServiceBookingDataFactory
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function build(
        CarbonPeriod $endPeriod,
        array $supplierIds,
        ?CarbonPeriod $startPeriod = null,
        array $serviceTypes = [],
        array $managerIds = []
    ): array {
        $bookingsData = Booking::query()
            ->addSelect('bookings.*')
            ->selectRaw(
                'COALESCE(booking_other_details.date, booking_airport_details.date, booking_transfer_details.date_start) as date_start'
            )
            ->selectRaw(
                'COALESCE(booking_other_details.date, booking_airport_details.date, booking_transfer_details.date_end, booking_transfer_details.date_start) as date_end'
            )
            ->selectRaw(
                "(SELECT title FROM supplier_services_translation WHERE language = 'ru' AND translatable_id = COALESCE(booking_airport_details.service_id, booking_other_details.service_id, booking_transfer_details.service_id)) as service_title"
            )
            ->selectRaw(
                "(SELECT name FROM clients WHERE id = orders.client_id) as client_name"
            )
            ->selectRaw(
                "(SELECT name FROM suppliers WHERE EXISTS (SELECT 1 FROM supplier_services WHERE supplier_services.supplier_id = suppliers.id AND supplier_services.id = COALESCE(booking_airport_details.service_id, booking_other_details.service_id, booking_transfer_details.service_id))) as supplier_name"
            )
            ->join('orders', 'orders.id', 'bookings.order_id')
            ->join('administrator_bookings', 'administrator_bookings.booking_id', 'bookings.id')
            ->leftJoin('booking_other_details', 'booking_other_details.booking_id', 'bookings.id')
            ->leftJoin('booking_transfer_details', 'booking_transfer_details.booking_id', 'bookings.id')
            ->leftJoin('booking_airport_details', 'booking_airport_details.booking_id', 'bookings.id')
            ->whereIn('bookings.status', [StatusEnum::CONFIRMED, StatusEnum::CANCELLED_FEE])
            ->whereExists(function (Query $builder) use ($supplierIds, $serviceTypes) {
                $builder->selectRaw(1)
                    ->from('supplier_services')
                    ->where(function (Query $query) {
                        $query->whereColumn('supplier_services.id', 'booking_other_details.service_id')
                            ->orWhereColumn('supplier_services.id', 'booking_transfer_details.service_id')
                            ->orWhereColumn('supplier_services.id', 'booking_airport_details.service_id');
                    })
                    ->whereIn('supplier_services.supplier_id', $supplierIds)
                    ->when(!empty($serviceTypes), function (Query $query) use ($serviceTypes) {
                        $query->whereIn('supplier_services.type', $serviceTypes);
                    });
            })
            ->where(function (Builder $builder) use ($managerIds) {
                if (!empty($managerIds)) {
                    $builder->whereIn('administrator_bookings.administrator_id', $managerIds);
                }
            })
            ->where(function (Builder $builder) use ($startPeriod, $endPeriod) {
                $startPeriodCondition = !empty($startPeriod) ? [$startPeriod->getStartDate(), $startPeriod->getEndDate()] : null;
                $endPeriodCondition = [$endPeriod->getStartDate(), $endPeriod->getEndDate()];

                $builder->where(function (Builder $query) use ($startPeriodCondition, $endPeriodCondition) {
                    $query->whereBetween('booking_other_details.date', $endPeriodCondition);
                    if (!empty($startPeriodCondition)) {
                        $query->whereBetween('booking_other_details.date', $startPeriodCondition);
                    }
                });

                $builder->orWhere(function (Builder $query) use ($startPeriodCondition, $endPeriodCondition) {
                    $query->whereBetween('booking_airport_details.date', $endPeriodCondition);
                    if (!empty($startPeriodCondition)) {
                        $query->whereBetween('booking_airport_details.date', $startPeriodCondition);
                    }
                });

                $builder->orWhere(function (Builder $query) use ($startPeriodCondition, $endPeriodCondition) {
                    $query->whereBetween('booking_transfer_details.date_start', $endPeriodCondition)
                        ->whereNull('booking_transfer_details.date_end');
                    if (!empty($startPeriodCondition)) {
                        $query->whereBetween('booking_transfer_details.date_start', $startPeriodCondition);
                    }
                });

                $builder->orWhere(function (Builder $query) use ($startPeriodCondition, $endPeriodCondition) {
                    $query->whereBetween('booking_transfer_details.date_end', $endPeriodCondition);
                    if (!empty($startPeriodCondition)) {
                        $query->whereBetween('booking_transfer_details.date_start', $startPeriodCondition);
                    }
                });
            })
            ->get();

        $bookingIds = $bookingsData->pluck('id')->toArray();
        $guestsIndexedByBookingId = [];
        if (count($bookingIds) > 0) {
            $guestsIndexedByBookingId = Guest::query()
                ->selectRaw('COALESCE(booking_car_bids.booking_id, booking_airport_guests.booking_id) as booking_id')
                ->leftJoin('booking_airport_guests', 'booking_airport_guests.guest_id', 'order_guests.id')
                ->leftJoin('booking_car_bid_guests', 'booking_car_bid_guests.guest_id', 'order_guests.id')
                ->leftJoin('booking_car_bids', 'booking_car_bids.id', 'booking_car_bid_guests.car_bid_id')
                ->where(function (Builder $builder) use ($bookingIds) {
                    $builder->whereIn('booking_airport_guests.booking_id', $bookingIds)
                        ->orWhereIn('booking_car_bids.booking_id', $bookingIds);
                })
                ->get()
                ->groupBy('booking_id')
                ->map(
                    fn(Collection $guests) => $guests->map(
                        fn(Guest $guest) => "{$guest->name} ({$guest->country_name})"
                    )->all()
                );
        }

        $services = [];
        if (!empty($serviceTypes)) {
            $services = array_map(fn($serviceType) => $this->translator->translateEnum(ServiceTypeEnum::from($serviceType)), $serviceTypes);
        }

        $suppliers = Supplier::whereIn('suppliers.id', $supplierIds)
            ->select('suppliers.name')
            ->get()
            ->pluck('name')
            ->toArray();

        return [
            'serviceNames' => $services,
            'supplierNames' => $suppliers,
            'guestsIndexedByBookingId' => $guestsIndexedByBookingId,
            'bookings' => $bookingsData,
        ];
    }
}
