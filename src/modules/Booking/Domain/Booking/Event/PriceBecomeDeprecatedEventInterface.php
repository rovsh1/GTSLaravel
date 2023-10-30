<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\Event;

use Module\Booking\Domain\Booking\ValueObject\BookingId;

interface PriceBecomeDeprecatedEventInterface
{
    public function bookingId(): BookingId;
}
