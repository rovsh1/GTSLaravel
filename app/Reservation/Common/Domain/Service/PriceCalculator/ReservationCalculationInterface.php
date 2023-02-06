<?php

namespace GTS\Reservation\Common\Domain\Service\PriceCalculator;

use GTS\Reservation\Common\Domain\Entity\ReservationInterface;

interface ReservationCalculationInterface
{
    public function calculate(ReservationInterface $reservation): ReservationPriceInterface;
}
