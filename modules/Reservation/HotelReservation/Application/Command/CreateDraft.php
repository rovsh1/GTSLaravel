<?php

namespace Module\Reservation\HotelReservation\Application\Command;

class CreateDraft
{
    public function __construct(
        public readonly int $reservationId
    ) {}
}
