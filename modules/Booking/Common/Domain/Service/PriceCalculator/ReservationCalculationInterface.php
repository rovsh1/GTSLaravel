<?php

namespace Module\Booking\Common\Domain\Service\PriceCalculator;

use Module\Booking\Common\Domain\Entity\ReservationInterface;

interface ReservationCalculationInterface
{
    public function calculate(ReservationInterface $booking): ReservationPriceInterface;
}
