<?php

namespace GTS\Reservation\Application\Command;

use GTS\Reservation\Domain\ValueObject\ReservationTypeEnum;

class CreateReservationRequest
{
    public function __construct(
        public readonly int $reservationId,
        public readonly ReservationTypeEnum $reservationType
    ) {}
}
