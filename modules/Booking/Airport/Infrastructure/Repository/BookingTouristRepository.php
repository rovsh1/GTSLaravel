<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Infrastructure\Repository;

use Illuminate\Support\Facades\DB;
use Module\Booking\Airport\Domain\Repository\BookingTouristRepositoryInterface;
use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Order\Domain\ValueObject\TouristId;

class BookingTouristRepository implements BookingTouristRepositoryInterface
{
    public function bind(BookingId $bookingId, TouristId $touristId): void
    {
        DB::table('booking_airport_tourists')->updateOrInsert(
            ['booking_airport_id' => $bookingId->value(), 'tourist_id' => $touristId->value()],
            ['booking_airport_id' => $bookingId->value(), 'tourist_id' => $touristId->value()]
        );
    }

    public function unbind(BookingId $bookingId, TouristId $touristId): void
    {
        DB::table('booking_hotel_room_tourists')
            ->where('booking_airport_id', $bookingId->value())
            ->whereTouristId($touristId->value())
            ->delete();
    }
}
