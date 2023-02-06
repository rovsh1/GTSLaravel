<?php

namespace GTS\Reservation\HotelReservation\Domain\ValueObject;

class Hotel
{
    public function __construct(
        private readonly int $id,
    ) {}

    public function id(): int
    {
        return $this->id;
    }
}
