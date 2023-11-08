<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Event;

use Module\Booking\Shared\Domain\Booking\ValueObject\BookingId;

interface PriceBecomeDeprecatedEventInterface
{
    public function bookingId(): BookingId;
}
