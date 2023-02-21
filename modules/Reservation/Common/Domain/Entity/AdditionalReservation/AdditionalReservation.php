<?php

namespace Module\Reservation\Common\Domain\Entity\AdditionalReservation;

use Module\Reservation\Common\Domain\Entity\ReservationItemInterface;
use Module\Reservation\Common\Domain\Service\PriceCalculator\AdditionalReservationCalculation;
use Module\Reservation\Common\Domain\Service\PriceCalculator\ReservationCalculationInterface;

class AdditionalReservation implements ReservationItemInterface
{
    public function getPriceCalculator(): ReservationCalculationInterface
    {
        return new AdditionalReservationCalculation();
    }
}
