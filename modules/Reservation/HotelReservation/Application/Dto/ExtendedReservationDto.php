<?php

namespace Module\Reservation\HotelReservation\Application\Dto;

use Custom\Framework\Foundation\Support\Dto\Dto;

class ExtendedReservationDto extends Dto
{
    public function __construct(
        public readonly ReservationDto $reservation,
        /** @var RoomDto[] $rooms */
        public readonly array          $rooms
    ) {}
}
