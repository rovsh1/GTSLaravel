<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Repository;

use Sdk\Booking\ValueObject\BookingId;
use Sdk\Booking\ValueObject\GuestId;

interface AirportBookingGuestRepositoryInterface
{
    public function bind(BookingId $bookingId, GuestId $guestId): void;

    public function unbind(BookingId $bookingId, GuestId $guestId): void;
}
