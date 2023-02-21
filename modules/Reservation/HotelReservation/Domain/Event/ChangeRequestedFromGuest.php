<?php

namespace Module\Reservation\HotelReservation\Domain\Event;

use Module\Reservation\Common\Domain\Event\EventInterface;
use Module\Reservation\Common\Domain\Event\StatusEventInterface;

class ChangeRequestedFromGuest implements EventInterface, StatusEventInterface
{
    public function __construct(int $reservationId) {}
}
