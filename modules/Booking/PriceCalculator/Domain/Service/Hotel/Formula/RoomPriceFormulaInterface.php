<?php

namespace Module\Booking\PriceCalculator\Domain\Service\Hotel\Formula;

use Module\Booking\PriceCalculator\Domain\ValueObject\CalculationResult;

interface RoomPriceFormulaInterface
{
    public function evaluate(int|float $dayPrice): CalculationResult;
}