<?php

namespace GTS\Reservation\Common\Domain\Entity;

use GTS\Reservation\Common\Domain\Service\PriceCalculator\ReservationCalculationInterface;

interface ReservationInterface
{
    public function getPriceCalculator(): ReservationCalculationInterface;

    public function voucherFactory(): ReservationCalculationInterface;
}
