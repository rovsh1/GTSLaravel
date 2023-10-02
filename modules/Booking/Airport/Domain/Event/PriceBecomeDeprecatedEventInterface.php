<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Domain\Event;

interface PriceBecomeDeprecatedEventInterface
{
    public function bookingId(): int;
}
