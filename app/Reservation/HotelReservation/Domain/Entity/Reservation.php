<?php

namespace GTS\Reservation\HotelReservation\Domain\Entity;

use GTS\Reservation\Common\Domain\Entity\ReservationItemInterface;
use GTS\Reservation\Common\Domain\Entity\ReservationRequestableInterface;
use GTS\Reservation\Common\Domain\Service\PriceCalculator\ReservationCalculationInterface;
use GTS\Reservation\HotelReservation\Domain\Service\PriceCalculator\ReservationCalculation;

class Reservation implements ReservationItemInterface, ReservationRequestableInterface
{
    public function getPriceCalculator(): ReservationCalculationInterface
    {
        return new ReservationCalculation();
    }
}
