<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Repository;

use Illuminate\Support\Facades\DB;
use Module\Booking\Shared\Domain\Booking\Repository\HotelBooking\BookingGuestRepositoryInterface;
use Sdk\Booking\ValueObject\GuestId;
use Sdk\Booking\ValueObject\HotelBooking\AccommodationId;

class AccommodationGuestRepository implements BookingGuestRepositoryInterface
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
