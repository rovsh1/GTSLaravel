<?php

namespace Module\Reservation\Common\Domain\Entity\ReservationGroup;

use Module\Reservation\Common\Domain\Entity\ReservationInterface;
use Module\Reservation\Common\Domain\Service\PriceCalculator\ReservationCalculationInterface;
use Module\Reservation\Common\Domain\Service\PriceCalculator\ReservationGroupCalculation;

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
