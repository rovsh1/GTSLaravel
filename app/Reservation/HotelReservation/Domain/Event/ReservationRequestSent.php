<?php

namespace GTS\Reservation\HotelReservation\Domain\Event;

use GTS\Reservation\Common\Domain\Event\EventInterface;
use GTS\Reservation\Common\Domain\Event\RequestEventInterface;
use GTS\Reservation\Common\Domain\Event\StatusEventInterface;

class ReservationRequestSent implements EventInterface, StatusEventInterface, RequestEventInterface
{
    public function __construct(
        public readonly int $reservationId,
        public readonly int $requestId,
        public readonly int $fileGuid,
    ) {}
}
