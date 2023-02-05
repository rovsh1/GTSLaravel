<?php

namespace GTS\Reservation\HotelReservation\Application\Command;

use GTS\Reservation\Common\Domain\ValueObject\ReservationTypeEnum;

class CreateReservationRequest
{
    public function __construct(
        public readonly int $reservationId,
        public readonly ReservationTypeEnum $reservationType
    ) {}
}
