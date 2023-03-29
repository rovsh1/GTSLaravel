<?php

namespace Module\HotelOld\Domain\Event;

use Custom\Framework\Contracts\Event\DomainEventInterface;

class BookingOpened implements DomainEventInterface
{
    public function __construct(int $roomId, \DateTime $date) {}
}
