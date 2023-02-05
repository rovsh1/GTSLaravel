<?php

namespace GTS\Reservation\Domain\Event;

class GuestNotCheckIn implements EventInterface, StatusEventInterface
{
    public function __construct(int $reservationId) {}
}
