<?php

namespace GTS\Reservation\Domain\Entity;

use GTS\Reservation\Domain\Service\PriceCalculator\AdditionalReservationCalculation;
use GTS\Reservation\Domain\Service\PriceCalculator\ReservationCalculationInterface;

class AdditionalReservation implements ReservationItemInterface
{
    public function getPriceCalculator(): ReservationCalculationInterface
    {
        return new AdditionalReservationCalculation();
    }
}
