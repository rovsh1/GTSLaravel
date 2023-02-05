<?php

namespace GTS\Reservation\Common\Domain\Entity\AdditionalReservation;

use GTS\Reservation\Common\Domain\Entity\ReservationItemInterface;
use GTS\Reservation\Common\Domain\Service\PriceCalculator\AdditionalReservationCalculation;
use GTS\Reservation\Common\Domain\Service\PriceCalculator\ReservationCalculationInterface;

class AdditionalReservation implements ReservationItemInterface
{
    public function getPriceCalculator(): ReservationCalculationInterface
    {
        return new AdditionalReservationCalculation();
    }
}
