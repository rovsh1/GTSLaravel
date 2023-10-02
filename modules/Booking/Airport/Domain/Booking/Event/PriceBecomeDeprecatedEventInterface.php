<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Domain\Booking\Event;

interface PriceBecomeDeprecatedEventInterface
{
    public function bookingId(): int;
}
