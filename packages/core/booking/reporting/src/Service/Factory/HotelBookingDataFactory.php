<?php

declare(strict_types=1);

namespace Pkg\Booking\Reporting\Service\Factory;

use App\Admin\Models\Booking\Booking;
use App\Admin\Models\Order\Guest;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Sdk\Booking\Enum\StatusEnum;

class HotelBookingDataFactory
{
    public function build(CarbonPeriod $endPeriod, ?CarbonPeriod $startPeriod = null, array $managerIds = []): array
    {
        $startPeriodCondition = !empty($startPeriod) ? [$startPeriod->getStartDate(), $startPeriod->getEndDate()] : null;
        $endPeriodCondition = [$endPeriod->getStartDate(), $endPeriod->getEndDate()];

        $hotelBookingsQuery = Booking::query()
            ->addSelect('bookings.*')
            ->addSelect('booking_hotel_details.hotel_id as hotel_id')
            ->selectRaw(
                '(select COALESCE(SUM(sum), 0) from supplier_payment_landings where supplier_payment_landings.booking_id = bookings.id) as payed_amount'
            )
            ->selectRaw(
                "(SELECT name FROM hotels WHERE id = booking_hotel_details.hotel_id) as hotel_name"
            )
            ->addSelect('booking_hotel_details.date_start')
            ->addSelect('booking_hotel_details.date_end')
            ->join('administrator_bookings', 'administrator_bookings.booking_id', 'bookings.id')
            ->join('booking_hotel_details', 'booking_hotel_details.booking_id', 'bookings.id')
            ->whereIn('bookings.status', [StatusEnum::CONFIRMED, StatusEnum::CANCELLED_FEE])
            ->where(function (Builder $query) use ($startPeriodCondition, $endPeriodCondition) {
                $query->whereBetween('booking_hotel_details.date_end', $endPeriodCondition);
                if (!empty($startPeriodCondition)) {
                    $query->whereBetween('booking_hotel_details.date_start', $startPeriodCondition);
                }
            })
            ->where(function (Builder $builder) use ($managerIds) {
                if (!empty($managerIds)) {
                    $builder->whereIn('administrator_bookings.administrator_id', $managerIds);
                }
            });

        $bookings = DB::query()
            ->fromSub($hotelBookingsQuery, 'b')
            ->addSelect('b.id')
            ->addSelect('b.hotel_name')
            ->addSelect('b.date_start')
            ->addSelect('b.date_end')
            ->selectRaw(
                '(SELECT COUNT(id) FROM booking_hotel_accommodations WHERE booking_hotel_accommodations.booking_id = b.id) as rooms_count'
            )
            ->selectRaw('DATEDIFF(b.date_end, b.date_start) AS nights_count')
            ->get();

        $bookingIds = $bookings->pluck('id')->toArray();

        $guestsIndexedByBookingId = [];
        if (count($bookingIds) > 0) {
            $guestsIndexedByBookingId = Guest::query()
                ->addSelect('booking_hotel_accommodations.booking_id')
                ->join('booking_hotel_room_guests', 'booking_hotel_room_guests.guest_id', 'order_guests.id')
                ->join('booking_hotel_accommodations', 'booking_hotel_accommodations.id', 'booking_hotel_room_guests.accommodation_id')
                ->whereIn('booking_hotel_accommodations.booking_id', $bookingIds)
                ->get()
                ->groupBy('booking_id')
                ->map(
                    fn(Collection $guests) => $guests->map(
                        fn(Guest $guest) => "{$guest->name} ({$guest->country_name})"
                    )->all()
                );
        }

        return [
            'bookings' => $bookings->toArray(),
            'guestsIndexedByBookingId' => count($guestsIndexedByBookingId) > 0
                ? $guestsIndexedByBookingId->toArray()
                : [],
        ];
    }
}
