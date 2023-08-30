<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Infrastructure\Repository;

use Illuminate\Support\Facades\DB;
use Module\Booking\HotelBooking\Domain\Repository\BookingGuestRepositoryInterface;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\RoomBooking\RoomBookingId;
use Module\Booking\Order\Domain\ValueObject\GuestId;

class BookingGuestRepository implements BookingGuestRepositoryInterface
{
    public function bind(RoomBookingId $roomBookingId, GuestId $guestId): void
    {
        DB::table('booking_hotel_room_guests')->updateOrInsert(
            ['booking_hotel_room_id' => $roomBookingId->value(), 'guest_id' => $guestId->value()],
            ['booking_hotel_room_id' => $roomBookingId->value(), 'guest_id' => $guestId->value()],
        );
    }

    public function unbind(RoomBookingId $roomBookingId, GuestId $guestId): void
    {
        DB::table('booking_hotel_room_guests')
            ->where('booking_hotel_room_id', $roomBookingId->value())
            ->whereGuestId($guestId->value())
            ->delete();
    }
}
