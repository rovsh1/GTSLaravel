<?php

namespace Module\Reservation\Common\Domain\Entity\AirportReservation;

use Module\Reservation\Common\Domain\Entity\ReservationItemInterface;
use Module\Reservation\Common\Domain\Entity\ReservationRequestableInterface;
use Module\Reservation\Common\Domain\Service\PriceCalculator\AirportReservationCalculation;
use Module\Reservation\Common\Domain\Service\PriceCalculator\ReservationCalculationInterface;

class AirportReservation implements ReservationItemInterface, ReservationRequestableInterface
{
    public function getPriceCalculator(): ReservationCalculationInterface
    {
        return new AirportReservationCalculation();
    }
}
