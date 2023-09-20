<?php

namespace Gsdk\Form\Element;

class File extends Input
{
    protected array $options = [
        'inputType' => 'file'
    ];

    protected array $attributes = ['readonly', 'required', 'disabled', 'pattern', 'multiple'];

    public function isFileUpload(): bool
    {
        return true;
    }

    public function getInputName(): string
    {
        $inputName = parent::getInputName();
        if ($this?->multiple) {
            $inputName .= '[]';
        }

        return $inputName;
    }

//    protected function prepareValue($value)
//    {
//        return trim(filter_var($value));//, FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_STRIP_LOW
//    }
}
