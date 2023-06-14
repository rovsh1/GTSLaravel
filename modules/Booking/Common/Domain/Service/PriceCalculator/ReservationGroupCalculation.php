<?php

namespace Module\Booking\Common\Domain\Service\PriceCalculator;

use Module\Booking\Common\Domain\Entity\BookingInterface;

class ReservationGroupCalculation implements ReservationCalculationInterface
{
    public function calculate(BookingInterface $booking): ReservationPriceInterface
    {
        // TODO: Implement calculate() method.
    }
}
