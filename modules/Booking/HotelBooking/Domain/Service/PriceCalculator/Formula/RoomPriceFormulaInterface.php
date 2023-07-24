<?php

namespace Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Formula;

use Module\Booking\Common\Domain\Support\CalculationResult;

interface RoomPriceFormulaInterface
{
    public function evaluate(int|float $dayPrice): CalculationResult;
}