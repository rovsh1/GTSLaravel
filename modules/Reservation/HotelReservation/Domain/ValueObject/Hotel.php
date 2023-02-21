<?php

namespace Module\Reservation\HotelReservation\Domain\ValueObject;

class Hotel
{
    public function __construct(
        private readonly int $id,
        public readonly string $checkInTime,
        public readonly string $checkOutTime,
    ) {}

    public function id(): int
    {
        return $this->id;
    }
}
