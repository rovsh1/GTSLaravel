<?php

namespace GTS\Reservation\HotelReservation\Domain\Service\PriceCalculator;

use GTS\Reservation\Common\Domain\Service\PriceCalculator\ReservationPriceInterface;
use GTS\Reservation\HotelReservation\Domain\Entity\Reservation;

class ReservationCalculation
{
    public function calculate(Reservation $reservation): ReservationPriceInterface
    {
        // TODO: Implement calculate() method.
    }
}
