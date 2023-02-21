<?php

namespace Module\Reservation\HotelReservation\Application\Dto;

use Custom\Framework\Foundation\Support\Dto\Dto;
use Module\Reservation\HotelReservation\Domain\Entity\Reservation;

class ReservationDto extends Dto
{
    public function __construct(
        public readonly int       $id,
        public readonly HotelInfo $hotelInfo
    ) {}

    public static function fromEntity(Reservation $reservation): static
    {
        return new static(
            $reservation->id()
        );
    }

    public static function collectionFromEntity(array $reservations): array
    {
        return array_map(fn($reservation) => static::fromEntity($reservation), $reservations);
    }
}
