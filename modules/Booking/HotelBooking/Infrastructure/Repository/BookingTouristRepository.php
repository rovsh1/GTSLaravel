<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Infrastructure\Repository;

use Illuminate\Support\Facades\DB;
use Module\Booking\HotelBooking\Domain\Repository\BookingTouristRepositoryInterface;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingId;
use Module\Booking\Order\Domain\ValueObject\TouristId;

class BookingTouristRepository implements BookingTouristRepositoryInterface
{
    public function bind(RoomBookingId $roomBookingId, TouristId $touristId): void
    {
        DB::table('booking_hotel_room_tourists')->updateOrInsert(
            ['booking_hotel_room_id' => $roomBookingId->value(), 'tourist_id' => $touristId->value()],
            ['booking_hotel_room_id' => $roomBookingId->value(), 'tourist_id' => $touristId->value()],
        );
    }

    public function unbind(RoomBookingId $roomBookingId, TouristId $touristId): void
    {
        DB::table('booking_hotel_room_tourists')
            ->where('booking_hotel_room_id', $roomBookingId->value())
            ->whereTouristId($touristId->value())
            ->delete();
    }
}
