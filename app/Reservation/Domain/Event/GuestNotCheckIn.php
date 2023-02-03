<?php

namespace GTS\Reservation\Domain\Event;

use GTS\Shared\Domain\Event\EventInterface;

class GuestNotCheckIn implements EventInterface
{
    public function __construct(int $reservationId) {}
}
