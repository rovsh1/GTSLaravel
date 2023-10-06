<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\HotelBooking\Repository;

use Illuminate\Support\Facades\DB;
use Module\Booking\Domain\HotelBooking\Repository\BookingGuestRepositoryInterface;
use Module\Booking\Domain\HotelBooking\ValueObject\Details\RoomBooking\RoomBookingId;
use Module\Booking\Domain\Shared\ValueObject\GuestId;

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
