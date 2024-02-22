<?php

namespace App\Admin\Support\Format;

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

        return __($value);
    }
}