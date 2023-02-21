<?php

namespace Module\Reservation\Common\Domain\Service\PriceCalculator;

use Module\Reservation\Common\Domain\Entity\ReservationInterface;

interface ReservationCalculationInterface
{
    public function calculate(ReservationInterface $reservation): ReservationPriceInterface;
}
