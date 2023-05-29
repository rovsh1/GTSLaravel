<?php

namespace Module\Hotel\Domain\Event;

use Sdk\Module\Contracts\Event\DomainEventInterface;

class BookingOpened implements DomainEventInterface
{
    public function __construct(int $roomId, \DateTime $date) {}
}
