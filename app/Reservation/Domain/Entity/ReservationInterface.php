<?php

namespace GTS\Reservation\Domain\Entity;

use GTS\Reservation\Domain\Service\PriceCalculator\ReservationCalculationInterface;

interface ReservationInterface
{
    public function getPriceCalculator(): ReservationCalculationInterface;

    public function voucherFactory(): ReservationCalculationInterface;
}
