<?php

namespace Gsdk\Form\Element;

class File extends Input
{
    protected array $options = [
        'inputType' => 'file'
    ];

    protected array $attributes = ['readonly', 'required', 'disabled', 'pattern', 'multiple'];

    protected array $attributes = ['readonly', 'required', 'disabled', 'placeholder', 'multiple'];

    public function isFileUpload(): bool
    {
        return true;
    }

//    protected function prepareValue($value)
//    {
//        return trim(filter_var($value));//, FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_STRIP_LOW
//    }
}
