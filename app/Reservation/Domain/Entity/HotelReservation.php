<?php

namespace GTS\Reservation\Domain\Entity;

use GTS\Reservation\Domain\Service\PriceCalculator\HotelReservationCalculation;
use GTS\Reservation\Domain\Service\PriceCalculator\ReservationCalculationInterface;

class HotelReservation implements ReservationItemInterface, ReservationRequestableInterface
{
    public function getPriceCalculator(): ReservationCalculationInterface
    {
        return new HotelReservationCalculation();
    }
}
