<?php

namespace Module\Booking\Domain\HotelBooking\Service\PriceCalculator\Support;

abstract class FormulaUtil
{
    public static function calculatePercent(int|float $value, int $percent): float|int
    {
        return $value * $percent / 100;
    }
}
