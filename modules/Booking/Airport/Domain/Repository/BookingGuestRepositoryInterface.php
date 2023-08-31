<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Domain\Repository;

use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\Order\Domain\ValueObject\GuestId;

interface BookingGuestRepositoryInterface
{
    public function bind(BookingId $bookingId, GuestId $guestId): void;

    public function unbind(BookingId $bookingId, GuestId $guestId): void;
}
