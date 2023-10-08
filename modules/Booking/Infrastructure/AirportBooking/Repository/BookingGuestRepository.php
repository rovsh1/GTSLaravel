<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\AirportBooking\Repository;

use Illuminate\Support\Facades\DB;
use Module\Booking\Deprecated\AirportBooking\Repository\BookingGuestRepositoryInterface;
use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Booking\Domain\Shared\ValueObject\GuestId;

class BookingGuestRepository implements BookingGuestRepositoryInterface
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
