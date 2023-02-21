<?php

namespace Module\Reservation\HotelReservation\Application\Dto;

use Custom\Framework\Foundation\Support\Dto\Dto;

class HotelInfo extends Dto
{
    public function __construct(
        public readonly int $id,
        public readonly string $checkInTime,
        public readonly string $checkOutTime,
    ) {}
}
