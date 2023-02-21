<?php

namespace Module\Reservation\HotelReservation\Application\Command;

use Module\Reservation\Common\Domain\ValueObject\ReservationTypeEnum;

class CreateReservationRequest
{
    public function __construct(
        public readonly int $reservationId,
        public readonly ReservationTypeEnum $reservationType
    ) {}
}
