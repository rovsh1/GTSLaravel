<?php

namespace Module\Booking\Common\Domain\Entity\AdditionalReservation;

use Module\Booking\Common\Domain\Entity\ReservationItemInterface;
use Module\Booking\PriceCalculator\Domain\Service\AdditionalReservationCalculation;
use Module\Booking\PriceCalculator\Domain\Service\ReservationCalculationInterface;

class AdditionalReservation implements ReservationItemInterface
{
    public function getPriceCalculator(): ReservationCalculationInterface
    {
        return new AdditionalReservationCalculation();
    }
}
