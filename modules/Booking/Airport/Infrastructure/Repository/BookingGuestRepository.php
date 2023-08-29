<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Infrastructure\Repository;

use Illuminate\Support\Facades\DB;
use Module\Booking\Airport\Domain\Repository\BookingGuestRepositoryInterface;
use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Order\Domain\ValueObject\GuestId;

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
