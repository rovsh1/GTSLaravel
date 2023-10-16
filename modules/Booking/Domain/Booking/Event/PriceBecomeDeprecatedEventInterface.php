<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\Event;

interface PriceBecomeDeprecatedEventInterface
{
    public function bookingId(): int;
}
