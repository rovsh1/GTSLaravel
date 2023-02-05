<?php

namespace GTS\Reservation\Domain\Entity;

use GTS\Reservation\Domain\Service\PriceCalculator\ReservationGroupCalculation;
use GTS\Reservation\Domain\Service\PriceCalculator\ReservationCalculationInterface;

class ReservationGroup implements ReservationInterface
{
    public function __construct(
        private int $hotelReservationId,
    ) {}

    public function getPriceCalculator(): ReservationCalculationInterface
    {
        return new ReservationGroupCalculation();
    }
}
