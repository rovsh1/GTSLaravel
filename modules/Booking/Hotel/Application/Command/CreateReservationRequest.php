<?php

namespace Module\Booking\Hotel\Application\Command;

use Module\Booking\Common\Domain\ValueObject\ReservationTypeEnum;

class CreateReservationRequest
{
    public function __construct(
        public readonly int $reservationId,
        public readonly ReservationTypeEnum $reservationType
    ) {}
}
