<?php

namespace Module\Booking\Common\Domain\Entity\AdditionalReservation;

use Module\Booking\Common\Domain\Entity\ReservationItemInterface;
use Module\Booking\Common\Domain\Service\PriceCalculator\AdditionalReservationCalculation;
use Module\Booking\Common\Domain\Service\PriceCalculator\ReservationCalculationInterface;

class AdditionalReservation implements ReservationItemInterface
{
    public function getPriceCalculator(): ReservationCalculationInterface
    {
        return new AdditionalReservationCalculation();
    }
}
