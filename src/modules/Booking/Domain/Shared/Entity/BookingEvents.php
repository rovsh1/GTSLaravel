<?php

namespace Module\Booking\Domain\Shared\Entity;

class BookingEvents implements BookingInterface
{
    public function __construct(
        array $events,
    ) {}
}
