<?php

namespace App\Hotel\Support\Format;

use Carbon\CarbonPeriod;
use Gsdk\Format\Rules\RuleInterface;

class PeriodRule implements RuleInterface
{
    /**
     * @param CarbonPeriod $value
     * @param $format
     * @return string
     */
    public function format(mixed $value, $format = null): string
    {
        if ($value === null) {
            return '';
        }
        return $value->getStartDate()->format('d.m.Y') . ' - ' . $value->getEndDate()?->format('d.m.Y');
    }
}
