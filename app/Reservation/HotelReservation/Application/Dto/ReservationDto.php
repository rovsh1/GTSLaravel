<?php

namespace GTS\Reservation\HotelReservation\Application\Dto;

use Custom\Framework\Foundation\Support\Dto\Dto;

class ReservationDto extends Dto
{
    public function __construct(
        public readonly int $id,
    ) {}
}
