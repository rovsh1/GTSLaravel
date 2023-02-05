<?php

namespace GTS\Reservation\HotelReservation\Domain\Event;

use GTS\Reservation\Common\Domain\Event\EditEventInterface;
use GTS\Reservation\Common\Domain\Event\EventInterface;
use GTS\Reservation\Common\Domain\ValueObject\ReservationTypeEnum;

class ReservationDeleted implements EventInterface, EditEventInterface
{
    public function __construct(
        public readonly int $reservationId,
        public readonly ReservationTypeEnum $reservationType,
        public readonly string $reservationName,
    ) {}
}
