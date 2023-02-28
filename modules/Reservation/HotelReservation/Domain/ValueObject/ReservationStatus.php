<?php

namespace Module\Reservation\HotelReservation\Domain\ValueObject;

use Module\Reservation\Common\Domain\ValueObject\ReservationStatusEnum;

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
