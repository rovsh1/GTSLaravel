<?php

namespace GTS\Reservation\Domain\Event;

class ChangeRequestedFromGuest implements EventInterface, StatusEventInterface
{
    public function __construct(int $reservationId) {}
}
