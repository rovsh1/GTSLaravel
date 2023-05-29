<?php

namespace Module\HotelOld\Domain\Event;

class BookingOpened implements \Sdk\Module\Contracts\Event\DomainEventInterface
{
    public function __construct(int $roomId, \DateTime $date) {}
}
