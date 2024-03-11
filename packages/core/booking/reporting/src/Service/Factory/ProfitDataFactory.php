<?php

declare(strict_types=1);

namespace Pkg\Booking\Reporting\Service\Factory;

use App\Admin\Models\Booking\Booking;
use App\Admin\Models\Order\Guest;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Module\Booking\Shared\Infrastructure\Enum\StatusSettingsEntityEnum;
use Sdk\Booking\Enum\StatusEnum;
use Sdk\Shared\Enum\CurrencyEnum;
use Shared\Contracts\Adapter\CurrencyRateAdapterInterface;

class ProfitDataFactory
{
    public function __construct(
        private readonly CurrencyRateAdapterInterface $currencyRateAdapter,
    ) {
    }

    public function build(CarbonPeriod $endPeriod, ?CarbonPeriod $startPeriod = null): array
    {
        $bookings = Booking::query()
            ->addSelect('bookings.*')
            ->addSelect('orders.client_id')
            ->addSelect('clients.name as client_name')
            ->addSelect('orders.status as order_status')
            ->addSelect('orders.currency')
            ->addSelect('orders.manual_client_penalty as order_manual_client_penalty')
            ->addSelect('booking_hotel_details.hotel_id as hotel_id')
            ->selectRaw(
                "(SELECT title FROM supplier_services_translation WHERE language = 'ru' AND translatable_id = COALESCE(booking_airport_details.service_id, booking_other_details.service_id, booking_transfer_details.service_id)) as service_title"
            )
            ->selectRaw(
                "(SELECT name FROM hotels WHERE id = booking_hotel_details.hotel_id) as hotel_name"
            )
            ->selectRaw(
                "(SELECT presentation FROM administrators WHERE id = administrator_orders.administrator_id) as administrator_name"
            )
            ->selectRaw(
                '(SELECT name_ru FROM booking_status_settings WHERE status = orders.status AND entity_type = ' . StatusSettingsEntityEnum::ORDER->value . ') as order_status_name'
            )
            ->selectRaw(
                'COALESCE(booking_other_details.date, booking_airport_details.date, booking_hotel_details.date_start, booking_transfer_details.date_start) as date_start'
            )
            ->selectRaw(
                'COALESCE(booking_other_details.date, booking_airport_details.date, booking_hotel_details.date_end, booking_transfer_details.date_end, booking_transfer_details.date_start) as date_end'
            )
            ->join('orders', 'orders.id', 'bookings.order_id')
            ->join('administrator_orders', 'administrator_orders.order_id', 'orders.id')
            ->join('clients', 'clients.id', 'orders.client_id')
            ->leftJoin('booking_hotel_details', 'booking_hotel_details.booking_id', 'bookings.id')
            ->leftJoin('booking_other_details', 'booking_other_details.booking_id', 'bookings.id')
            ->leftJoin('booking_transfer_details', 'booking_transfer_details.booking_id', 'bookings.id')
            ->leftJoin('booking_airport_details', 'booking_airport_details.booking_id', 'bookings.id')
            ->whereIn('bookings.status', [StatusEnum::CONFIRMED, StatusEnum::CANCELLED_FEE])
            ->where(function (Builder $builder) use ($startPeriod, $endPeriod) {
                $startPeriodCondition = !empty($startPeriod) ? [
                    $startPeriod->getStartDate(),
                    $startPeriod->getEndDate()
                ] : null;
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
                    $query->whereBetween('booking_hotel_details.date_end', $endPeriodCondition);
                    if (!empty($startPeriodCondition)) {
                        $query->whereBetween('booking_hotel_details.date_start', $startPeriodCondition);
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

        $orderIds = $bookings->pluck('order_id')->unique()->toArray();
        $guests = [];
        if (count($orderIds) > 0) {
            $guests = Guest::whereIn('order_id', $orderIds)
                ->get()
                ->groupBy('order_id')
                ->map(
                    fn(Collection $guests) => $guests->map(
                        fn(Guest $guest) => "{$guest->name} ({$guest->country_name})"
                    )->all()
                );
        }

        return $bookings->groupBy(['currency', 'order_id'])->map(
            function (Collection $currencies) use ($guests) {
                return $currencies->map(function (Collection $bookings) use ($guests) {
                    $firstBooking = $bookings->first();
                    $currency = $firstBooking->client_currency->name;
                    $orderData = [
                        'id' => $firstBooking->order_id,
                        'client_name' => $firstBooking->client_name,
                        'currency' => $currency,
                        'manager' => $firstBooking->administrator_name,
                        'payed_amount' => (float)$firstBooking->payed_amount,
                        'guests' => $guests->get($firstBooking->order_id, []),
                        'status' => $firstBooking->order_status_name,
                        'external_id' => $firstBooking->order_external_id,
                        'period_start' => null,
                        'period_end' => null,
                        'service_amount' => 0,
                        'hotel_amount' => 0,
                        'total_amount' => 0,
                        'total_supplier_amount' => 0,
                        'hotels' => [],
                        'services' => [],
                    ];
                    foreach ($bookings as $booking) {
                        $amount = $booking->client_penalty;
                        if ($amount <= 0) {
                            $amount = $booking->client_manual_price ?? $booking->client_price;
                        }
                        $orderData['total_amount'] += $amount;
                        if ($booking->hotel_id !== null) {
                            $orderData['hotel_amount'] += $amount;
                            $hotelName = $booking->hotel_name;
                            if (!in_array($hotelName, $orderData['hotels'])) {
                                $orderData['hotels'][] = $hotelName;
                            }
                        } else {
                            $orderData['service_amount'] += $amount;
                            $serviceTitle = $booking->service_title;
                            if (!in_array($serviceTitle, $orderData['services'])) {
                                $orderData['services'][] = $serviceTitle;
                            }
                        }

                        if (empty($orderData['period_start'])) {
                            $orderData['period_start'] = strtotime($booking->date_start);
                        } else {
                            $orderData['period_start'] = min($orderData['period_start'], strtotime($booking->date_start));
                        }
                        $orderData['period_end'] = max($orderData['period_end'], strtotime($booking->date_end));

                        $supplierAmount = $booking->supplier_penalty;
                        if ($supplierAmount <= 0) {
                            $supplierAmount = $booking->supplier_manual_price ?? $booking->supplier_price;
                        }
                        $supplierAmount = $this->currencyRateAdapter->convertNetRate(
                            $supplierAmount,
                            $booking->supplier_currency,
                            CurrencyEnum::from($currency),
                            'UZ'
                        );
                        $orderData['total_supplier_amount'] += $supplierAmount;
                    }
                    if ($firstBooking->order_manual_client_penalty > 0) {
                        $orderData['total_amount'] = $firstBooking->order_manual_client_penalty;
                        $orderData['hotel_amount'] = 0;
                        $orderData['service_amount'] = 0;
                    }
                    $orderData['profit_amount'] = $orderData['total_amount'] - $orderData['total_supplier_amount'];

                    return $orderData;
                });
            }
        )->toArray();
    }
}
