<?php

namespace Module\Booking\Common\Domain\Entity\ReservationGroup;

use Module\Booking\Common\Domain\Entity\ReservationInterface;
use Module\Booking\Common\Domain\Service\PriceCalculator\ReservationCalculationInterface;
use Module\Booking\Common\Domain\Service\PriceCalculator\ReservationGroupCalculation;

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
