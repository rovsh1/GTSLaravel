<?php

namespace GTS\Reservation\Domain\Entity;

use GTS\Reservation\Domain\Service\PriceCalculator\AirportReservationCalculation;
use GTS\Reservation\Domain\Service\PriceCalculator\ReservationCalculationInterface;

class AirportReservation implements ReservationItemInterface, ReservationRequestableInterface
{
    public function getPriceCalculator(): ReservationCalculationInterface
    {
        return new AirportReservationCalculation();
    }
}
