<?php

namespace GTS\Reservation\Domain\Service\PriceCalculator;

use GTS\Reservation\Domain\Entity\ReservationInterface;

interface ReservationCalculationInterface
{
    public function calculate(ReservationInterface $reservation): ReservationPriceInterface;
}
