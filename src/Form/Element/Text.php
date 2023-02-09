<?php

namespace Gsdk\Form\Element;

class Text extends Input
{
    protected array $options = [
        'inputType' => 'text'
    ];

    protected array $attributes = ['readonly', 'required', 'disabled', 'autocomplete', 'list', 'maxlength', 'minlength', 'pattern', 'placeholder', 'size', 'spellcheck'];

    protected function prepareValue($value)
    {
        return trim(filter_var($value));//, FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_STRIP_LOW
    }
}
