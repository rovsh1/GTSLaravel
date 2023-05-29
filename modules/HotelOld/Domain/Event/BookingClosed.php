<?php

namespace Module\HotelOld\Domain\Event;

use Sdk\Module\Contracts\Event\DomainEventInterface;

class BookingClosed implements DomainEventInterface
{
    public function __construct(int $roomId, \DateTime $date) {}
}
