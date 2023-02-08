<?php

namespace Gsdk\Form\Element;

class Url extends Text
{
    protected array $options = [
        'inputType' => 'url'
    ];

    public function checkValue($value): bool
    {
        return '' === $value || filter_var($value, FILTER_VALIDATE_URL);
    }
}
