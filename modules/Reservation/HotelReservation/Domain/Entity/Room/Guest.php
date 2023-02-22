<?php

namespace Module\Reservation\HotelReservation\Domain\Entity\Room;

use Module\Reservation\HotelReservation\Domain\ValueObject\GenderEnum;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class Guest implements ValueObjectInterface
{
    public function __construct(
        public readonly string     $fullName,
        public readonly int        $nationalityId,
        public readonly GenderEnum $gender,
    ) {}
}
