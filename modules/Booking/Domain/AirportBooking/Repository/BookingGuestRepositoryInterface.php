<?php

declare(strict_types=1);

namespace Module\Booking\Domain\AirportBooking\Repository;

use Module\Booking\Domain\Order\ValueObject\GuestId;
use Module\Booking\Domain\Shared\ValueObject\BookingId;

interface BookingGuestRepositoryInterface
{
    public function bind(BookingId $bookingId, GuestId $guestId): void;

    public function unbind(BookingId $bookingId, GuestId $guestId): void;
}
