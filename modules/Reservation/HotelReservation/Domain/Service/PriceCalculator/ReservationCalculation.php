<?php

namespace Module\Reservation\HotelReservation\Domain\Service\PriceCalculator;

use Module\Reservation\Common\Domain\Service\PriceCalculator\ReservationPriceInterface;
use Module\Reservation\HotelReservation\Domain\Entity\Reservation;

class ReservationCalculation
{
    public function calculate(Reservation $reservation): ReservationPriceInterface
    {
        // TODO: Implement calculate() method.
    }
}
