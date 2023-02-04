<?php

namespace GTS\Reservation\Domain\Service\PriceCalculator;

use GTS\Reservation\Domain\Entity\ReservationInterface;

interface ReservationPriceInterface
{
    public function calculate(ReservationInterface $reservation);
}
