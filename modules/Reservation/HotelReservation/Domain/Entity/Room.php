<?php

namespace Module\Reservation\HotelReservation\Domain\Entity;

use Module\Reservation\HotelReservation\Domain\Entity\Room\Guest;

class Room
{
    public function __construct(
        public readonly int    $id,
        /** @var Guest[] $guests */
        public readonly array  $guests,
        public readonly int    $rateId,
        public readonly ?string $checkInTime,
        public readonly ?string $checkOutTime,
    ) {}
}
