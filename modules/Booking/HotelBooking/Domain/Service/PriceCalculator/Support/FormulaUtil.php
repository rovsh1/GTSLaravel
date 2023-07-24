<?php

namespace Module\Booking\HotelBooking\Domain\Service\PriceCalculator\Support;

abstract class FormulaUtil
{
    public static function calculatePercent(int|float $value, int $percent): float|int
    {
        return $value * $percent / 100;
    }

    public static function calculateConditionMarkup(
        float $value,
        int|float|null $earlyCheckInPercent,
        int|float|null $lateCheckOutPercent
    ): float {
        $earlyCheckInValue = 0;
        if ($earlyCheckInPercent !== null) {
            $earlyCheckInValue = $value * $earlyCheckInPercent / 100;
        }
        $lateCheckOutValue = 0;
        if ($lateCheckOutPercent !== null) {
            $lateCheckOutValue = $value * $lateCheckOutPercent / 100;
        }

        return $earlyCheckInValue + $lateCheckOutValue;
    }
}
