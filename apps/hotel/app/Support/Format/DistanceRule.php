<?php

namespace App\Hotel\Support\Format;

use Gsdk\Format\Rules\RuleInterface;

class DistanceRule implements RuleInterface
{
    public function format($valueInMeters, $format = 'km'): string
    {
        return match ($format) {
            'm' => $valueInMeters . ' м',
            'km' => round($valueInMeters / 1000, 2) . ' км',
            default => round($valueInMeters / 1000, 2) . ' км',
        };
    }
}
