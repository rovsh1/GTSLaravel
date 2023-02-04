<?php

namespace GTS\Reservation\Domain\Entity;

class ReservationGroup implements ReservationInterface
{
    public function __construct(
        private int $hotelReservationId,
    ) {}
}
