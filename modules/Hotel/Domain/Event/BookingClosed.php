<?php

namespace Module\Hotel\Domain\Event;

class BookingClosed implements \Sdk\Module\Contracts\Event\DomainEventInterface
{
    public function __construct(int $roomId, \DateTime $date) {}
}
