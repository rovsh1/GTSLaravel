<?php

namespace Module\Hotel\Moderation\Domain\Hotel\Event;

class BookingClosed implements \Sdk\Module\Contracts\Event\DomainEventInterface
{
    public function __construct(int $roomId, \DateTime $date) {}
}
