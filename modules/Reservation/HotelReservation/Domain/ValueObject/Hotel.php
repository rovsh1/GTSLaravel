<?php

namespace Module\Reservation\HotelReservation\Domain\ValueObject;

use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class Hotel implements ValueObjectInterface
{
    public function __construct(
        private readonly int $id,
        public ?string       $checkInTime = null,
        public ?string       $checkOutTime = null,
    ) {}

    public function id(): int
    {
        return $this->id;
    }
}
