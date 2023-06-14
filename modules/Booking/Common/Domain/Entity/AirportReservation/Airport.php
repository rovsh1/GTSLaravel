<?php

namespace Module\Booking\Common\Domain\Entity\AirportReservation;

use Module\Booking\Common\Domain\Entity\BookingRequestableInterface;
use Module\Booking\Common\Domain\Entity\ReservationItemInterface;
use Module\Booking\PriceCalculator\Domain\Service\AirportReservationCalculation;
use Module\Booking\PriceCalculator\Domain\Service\ReservationCalculationInterface;

class Airport implements ReservationItemInterface, BookingRequestableInterface
{
    public function getPriceCalculator(): ReservationCalculationInterface
    {
        return new AirportReservationCalculation();
    }
}
