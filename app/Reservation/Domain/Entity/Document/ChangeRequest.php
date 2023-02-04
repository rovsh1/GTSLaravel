<?php

namespace GTS\Reservation\Domain\Entity\Document;

use GTS\Reservation\Domain\Entity\ReservationRequestableInterface;

class ChangeRequest
{
    public function __construct(
        private readonly ReservationRequestableInterface $reservation
    ) {}
}
