<?php

namespace GTS\Reservation\Common\Domain\Entity\ReservationGroup;

use GTS\Reservation\Common\Domain\Entity\ReservationInterface;
use GTS\Reservation\Common\Domain\Service\PriceCalculator\ReservationCalculationInterface;
use GTS\Reservation\Common\Domain\Service\PriceCalculator\ReservationGroupCalculation;

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
