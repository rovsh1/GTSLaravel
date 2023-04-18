<?php

namespace Module\Booking\Hotel\Domain\ValueObject;

use Module\Booking\Common\Domain\ValueObject\ReservationStatusEnum;

class ReservationStatus
{
    public function __construct(
        private ReservationStatusEnum $id,
    ) {}

    public function id(): ReservationStatusEnum
    {
        return $this->id;
    }
}
