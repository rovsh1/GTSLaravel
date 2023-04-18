<?php

namespace Module\Booking\Common\Domain\Entity\AirportReservation;

use Module\Booking\Common\Domain\Entity\ReservationItemInterface;
use Module\Booking\Common\Domain\Entity\ReservationRequestableInterface;
use Module\Booking\Common\Domain\Service\PriceCalculator\AirportReservationCalculation;
use Module\Booking\Common\Domain\Service\PriceCalculator\ReservationCalculationInterface;

class AirportReservation implements ReservationItemInterface, ReservationRequestableInterface
{
    public function getPriceCalculator(): ReservationCalculationInterface
    {
        return new AirportReservationCalculation();
    }
}
