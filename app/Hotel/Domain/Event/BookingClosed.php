<?php

namespace GTS\Hotel\Domain\Event;

use Custom\Framework\Contracts\Event\DomainEventInterface;

class BookingClosed implements DomainEventInterface
{
    public function __construct(int $roomId, \DateTime $date) {}
}
