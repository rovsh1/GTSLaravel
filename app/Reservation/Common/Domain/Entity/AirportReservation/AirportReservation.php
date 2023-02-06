<?php

namespace GTS\Reservation\Common\Domain\Entity\AirportReservation;

use GTS\Reservation\Common\Domain\Entity\ReservationItemInterface;
use GTS\Reservation\Common\Domain\Entity\ReservationRequestableInterface;
use GTS\Reservation\Common\Domain\Service\PriceCalculator\AirportReservationCalculation;
use GTS\Reservation\Common\Domain\Service\PriceCalculator\ReservationCalculationInterface;

class AirportReservation implements ReservationItemInterface, ReservationRequestableInterface
{
    public function getPriceCalculator(): ReservationCalculationInterface
    {
        return new AirportReservationCalculation();
    }
}
