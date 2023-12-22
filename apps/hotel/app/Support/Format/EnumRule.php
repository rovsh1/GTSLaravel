<?php

namespace App\Hotel\Support\Format;

use App\Shared\Support\Facades\Lang;
use Gsdk\Format\Rules\RuleInterface;

class EnumRule implements RuleInterface
{
    public function format(mixed $value, $format = null): string
    {
        if (empty($value) || is_scalar($value)) {
            return '';
        } elseif (!is_object($value) || !enum_exists($value::class)) {
            return '';
        }

        return Lang::translateEnum($value);
    }
}