<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\HotelBooking\Repository;

use Illuminate\Support\Facades\DB;
use Module\Booking\Shared\Domain\Booking\Repository\HotelBooking\BookingGuestRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\HotelBooking\AccommodationId;
use Module\Booking\Shared\Domain\Guest\ValueObject\GuestId;

class BookingGuestRepository implements BookingGuestRepositoryInterface
{
    public function bind(AccommodationId $accommodationId, GuestId $guestId): void
    {
        DB::table('booking_hotel_room_guests')->updateOrInsert(
            ['accommodation_id' => $accommodationId->value(), 'guest_id' => $guestId->value()],
            ['accommodation_id' => $accommodationId->value(), 'guest_id' => $guestId->value()],
        );
    }

    public function unbind(AccommodationId $accommodationId, GuestId $guestId): void
    {
        DB::table('booking_hotel_room_guests')
            ->where('accommodation_id', $accommodationId->value())
            ->whereGuestId($guestId->value())
            ->delete();
    }
}
