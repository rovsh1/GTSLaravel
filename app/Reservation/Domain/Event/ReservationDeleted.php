<?php

namespace GTS\Reservation\Domain\Event;

use GTS\Reservation\Domain\ValueObject\ReservationTypeEnum;

class ReservationDeleted implements EventInterface, EditEventInterface
{
    public function __construct(
        public readonly int $reservationId,
        public readonly ReservationTypeEnum $reservationType,
        public readonly string $reservationName,
    ) {}
}
