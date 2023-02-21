<?php

namespace Module\Reservation\HotelReservation\Domain\Event;

use Module\Reservation\Common\Domain\Event\EditEventInterface;
use Module\Reservation\Common\Domain\Event\EventInterface;
use Module\Reservation\Common\Domain\ValueObject\ReservationTypeEnum;

class ReservationDeleted implements EventInterface, EditEventInterface
{
    public function __construct(
        public readonly int $reservationId,
        public readonly ReservationTypeEnum $reservationType,
        public readonly string $reservationName,
    ) {}
}
