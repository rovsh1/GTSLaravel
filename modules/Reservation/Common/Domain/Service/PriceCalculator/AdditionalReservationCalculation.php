<?php

namespace Module\Reservation\Common\Domain\Service\PriceCalculator;

use Module\Reservation\Common\Domain\Entity\ReservationInterface;

class AdditionalReservationCalculation implements ReservationCalculationInterface
{
    public function calculate(ReservationInterface $reservation): ReservationPriceInterface
    {
        // TODO: Implement calculate() method.
    }
}
