<?php

namespace App\Hotel\Support\Format;

use Gsdk\Format\Format;
use Gsdk\Format\Rules\Date;

class TimeRule extends Date
{
    public function format(mixed $value, $format = null): string
    {
        $date = $this->dateFactory($value);
        if (null === $date) {
            return '';
        }
        $format = Format::getFormat($format ?? 'time');

        return $date->format($format);
    }
}
