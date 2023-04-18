<?php

namespace Module\Booking\Hotel\Domain\Service\PriceCalculator;

use Module\Booking\Common\Domain\Service\PriceCalculator\ReservationPriceInterface;
use Module\Booking\Hotel\Domain\Entity\Reservation;

class ReservationCalculation
{
    public function calculate(Reservation $reservation): ReservationPriceInterface
    {
        // TODO: Implement calculate() method.
    }
}
