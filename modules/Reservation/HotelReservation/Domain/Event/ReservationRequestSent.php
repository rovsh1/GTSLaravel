<?php

namespace Module\Reservation\HotelReservation\Domain\Event;

use Module\Reservation\Common\Domain\Event\EventInterface;
use Module\Reservation\Common\Domain\Event\RequestEventInterface;
use Module\Reservation\Common\Domain\Event\StatusEventInterface;

class ReservationRequestSent implements EventInterface, StatusEventInterface, RequestEventInterface
{
    public function __construct(
        public readonly int $reservationId,
        public readonly int $requestId,
        public readonly int $fileGuid,
    ) {}
}
