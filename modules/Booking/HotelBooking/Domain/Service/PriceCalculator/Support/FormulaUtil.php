<?php

namespace Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Support;

abstract class FormulaUtil
{
    public static function calculatePercent(int|float $value, int $percent): float|int
    {
        return $value * $percent / 100;
    }
}
