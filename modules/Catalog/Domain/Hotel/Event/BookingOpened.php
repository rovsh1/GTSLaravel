<?php

namespace Module\Catalog\Domain\Hotel\Event;

use Sdk\Module\Contracts\Event\DomainEventInterface;

class BookingOpened implements DomainEventInterface
{
    public function __construct(int $roomId, \DateTime $date) {}
}
