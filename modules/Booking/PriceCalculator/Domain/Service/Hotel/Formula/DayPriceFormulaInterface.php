<?php

namespace Module\Booking\PriceCalculator\Domain\Service\Hotel\Formula;

interface DayPriceFormulaInterface
{
    public function calculate(int|float $netValue): float;
}