<?php

namespace GTS\Reservation\Domain\Event;

use GTS\Reservation\Domain\ValueObject\ReservationTypeEnum;
use GTS\Shared\Domain\Event\EventInterface;

class ReservationDeleted implements EventInterface
{
    public function __construct(
        public readonly int $reservationId,
        public readonly ReservationTypeEnum $reservationType,
        public readonly string $reservationName,
    ) {}
}
