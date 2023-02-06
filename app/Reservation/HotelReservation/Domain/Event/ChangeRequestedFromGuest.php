<?php

namespace GTS\Reservation\HotelReservation\Domain\Event;

use GTS\Reservation\Common\Domain\Event\EventInterface;
use GTS\Reservation\Common\Domain\Event\StatusEventInterface;

class ChangeRequestedFromGuest implements EventInterface, StatusEventInterface
{
    public function __construct(int $reservationId) {}
}
