<?php

namespace Module\Pricing\Domain\Hotel\Support;

abstract class FormulaUtil
{
    public static function calculatePercent(int|float $value, int $percent): float|int
    {
        return $value * $percent / 100;
    }
}
