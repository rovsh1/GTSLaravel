<?php

namespace GTS\Reservation\HotelReservation\Application\Dto;

use Custom\Framework\Foundation\Support\Dto\Dto;

use GTS\Reservation\HotelReservation\Domain\Entity\Reservation;

class ReservationDto extends Dto
{
    public function __construct(
        public readonly int $id,
    ) {}

    public static function fromEntity(Reservation $reservation): static
    {
        return new static(
            $reservation->id()
        );
    }
}
