<?php

namespace Gsdk\Form\Element;

class Hidden extends Input
{
    protected array $options = [
        'inputType' => 'hidden',
        'nullValue' => ''
    ];

    public function isHidden(): bool
    {
        return true;
    }

    protected function prepareValue($value)
    {
        if ($this->nullValue === $value) {
            return null;
        }

        return parent::prepareValue($value);
    }
}
