<?php

namespace Module\Booking\PriceCalculator\Domain\Service\Hotel\Support;

abstract class FormulaUtil
{
    public static function calculatePercent(int|float $value, int $percent): float|int
    {
        return $value * $percent / 100;
    }

    public static function calculateConditionMarkup(
        float $value,
        int|float $earlyCheckInPercent,
        int|float $lateCheckOutPercent
    ): float {
        return $value * $earlyCheckInPercent / 100 + $value * $lateCheckOutPercent / 100;
    }
}