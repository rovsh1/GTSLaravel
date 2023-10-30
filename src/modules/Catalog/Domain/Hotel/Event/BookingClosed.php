<?php

namespace Module\Catalog\Domain\Hotel\Event;

class BookingClosed implements \Sdk\Module\Contracts\Event\DomainEventInterface
{
    public function __construct(int $roomId, \DateTime $date) {}
}
