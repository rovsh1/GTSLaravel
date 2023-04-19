<?php

namespace Gsdk\Grid\Column;

use Carbon\CarbonPeriod;

class DatePeriod extends AbstractColumn
{
    protected array $options = [
        'format' => 'd.m.Y'
    ];

    public function formatValue($value, $row = null)
    {
        if ($value instanceof CarbonPeriod) {
            return $value->getStartDate()->format($this->format)
                . ' - ' . $value->getEndDate()->format($this->format);
        } else {
            return '';
        }
    }
}
