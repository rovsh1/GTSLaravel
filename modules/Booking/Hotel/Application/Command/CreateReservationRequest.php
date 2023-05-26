<?php

namespace Module\Booking\Hotel\Application\Command;

use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;

class CreateReservationRequest
{
    public function __construct(
        public readonly int $reservationId,
        public readonly BookingTypeEnum $reservationType
    ) {}
}
