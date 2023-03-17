<?php

namespace Gsdk\Form\Element;

use Gsdk\Form\Support\Element\InputAttributes;

class Password extends Input
{
    protected array $options = [
        'inputType' => 'password'
    ];

    protected array $attributes = ['readonly', 'required', 'disabled', 'autocomplete', 'maxlength', 'minlength', 'pattern', 'placeholder'];

    public function getHtml(): string
    {
        return '<input'
            . ' type="' . ($this->inputType ?? 'text') . '"'
            . ' class="' . ($this->class ?? 'input-' . $this->inputType) . '"'
            . (new InputAttributes($this))->render($this->attributes)
            . ' value="">';
    }
}
