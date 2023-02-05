<?php

namespace GTS\Reservation\Domain\Entity\Document;

use GTS\Reservation\Domain\Entity\ReservationInterface;

class Voucher implements DocumentInterface
{
    public function __construct(
        private readonly ReservationInterface $reservation
    ) {}
}
