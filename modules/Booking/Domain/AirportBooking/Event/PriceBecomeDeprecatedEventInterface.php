<?php

declare(strict_types=1);

namespace Module\Booking\Domain\AirportBooking\Event;

interface PriceBecomeDeprecatedEventInterface
{
    public function bookingId(): int;
}
