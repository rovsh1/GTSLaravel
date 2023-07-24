<?php

namespace Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Formula;

interface DayPriceFormulaInterface
{
    public function calculate(int|float $netValue): float;
}