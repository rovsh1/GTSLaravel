<?php

namespace Gsdk\Form\Element;

class Email extends Input
{
    protected array $options = [
        'inputType' => 'email'
    ];

    protected array $attributes = ['readonly', 'required', 'disabled', 'maxlength', 'autocomplete', 'minlength', 'multiple', 'pattern', 'placeholder', 'size'];

    public function checkValue($value): bool
    {
        return (bool)filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    protected function prepareValue($value)
    {
        return $this->castValue($value, 'string');
    }
}
