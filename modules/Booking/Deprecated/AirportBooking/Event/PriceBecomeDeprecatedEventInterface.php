<?php

declare(strict_types=1);

namespace Module\Booking\Deprecated\AirportBooking\Event;

interface PriceBecomeDeprecatedEventInterface
{
    public function bookingId(): int;
}
