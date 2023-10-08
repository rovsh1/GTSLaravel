<?php

declare(strict_types=1);

namespace Module\Booking\Deprecated\AirportBooking\Repository;

use Module\Booking\Domain\ServiceBooking\ValueObject\BookingId;
use Module\Booking\Domain\Shared\ValueObject\GuestId;

interface BookingGuestRepositoryInterface
{
    public function bind(BookingId $bookingId, GuestId $guestId): void;

    public function unbind(BookingId $bookingId, GuestId $guestId): void;
}
