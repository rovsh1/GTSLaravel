<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Repository;

use Illuminate\Support\Facades\DB;
use Module\Booking\Shared\Domain\Booking\Repository\AirportBookingGuestRepositoryInterface;
use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\GuestId;

class AirportBookingGuestRepository implements AirportBookingGuestRepositoryInterface
{
    public function bind(BookingId $bookingId, GuestId $guestId): void
    {
        DB::table('booking_airport_guests')->updateOrInsert(
            ['booking_airport_id' => $bookingId->value(), 'guest_id' => $guestId->value()],
            ['booking_airport_id' => $bookingId->value(), 'guest_id' => $guestId->value()]
        );
    }

    public function unbind(BookingId $bookingId, GuestId $guestId): void
    {
        DB::table('booking_airport_guests')
            ->where('booking_airport_id', $bookingId->value())
            ->whereGuestId($guestId->value())
            ->delete();
    }
}
