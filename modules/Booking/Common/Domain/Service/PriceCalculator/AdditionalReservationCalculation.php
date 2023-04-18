<?php

namespace Module\Booking\Common\Domain\Service\PriceCalculator;

use Module\Booking\Common\Domain\Entity\ReservationInterface;

class AdditionalReservationCalculation implements ReservationCalculationInterface
{
    public function calculate(ReservationInterface $booking): ReservationPriceInterface
    {
        // TODO: Implement calculate() method.
    }
}
