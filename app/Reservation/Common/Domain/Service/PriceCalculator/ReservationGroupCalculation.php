<?php

namespace GTS\Reservation\Common\Domain\Service\PriceCalculator;

use GTS\Reservation\Common\Domain\Entity\ReservationInterface;

class ReservationGroupCalculation implements ReservationCalculationInterface
{
    public function calculate(ReservationInterface $reservation): ReservationPriceInterface
    {
        // TODO: Implement calculate() method.
    }
}
