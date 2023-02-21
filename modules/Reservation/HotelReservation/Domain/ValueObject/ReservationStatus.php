<?php

namespace Module\Reservation\HotelReservation\Domain\ValueObject;

class ReservationStatus
{
    public function __construct(
        private ?string $notes,
    ) {}
}
