<?php

namespace GTS\Reservation\Application\Command\HotelReservation;

class CreateDraft
{
    public function __construct(
        public readonly int $reservationId
    ) {}
}
