<?php

namespace GTS\Reservation\Domain\Entity\Document;

use GTS\Reservation\Domain\Entity\ReservationRequestableInterface;

class ReservationRequest implements DocumentInterface
{
    public function __construct(
        private readonly ReservationRequestableInterface $reservation
    ) {}
}
