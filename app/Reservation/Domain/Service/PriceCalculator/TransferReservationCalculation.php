<?php

namespace GTS\Reservation\Domain\Service\PriceCalculator;

use GTS\Reservation\Domain\Entity\ReservationInterface;

class TransferReservationCalculation implements ReservationCalculationInterface
{
    public function calculate(ReservationInterface $reservation): ReservationPriceInterface
    {
        // TODO: Implement calculate() method.
    }
}
