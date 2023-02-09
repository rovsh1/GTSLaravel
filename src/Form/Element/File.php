<?php

namespace Custom\Form\Element;

use Gsdk\Form\Element\Input;

class File extends Input
{
    protected array $options = [
        'inputType' => 'file'
    ];

    public function isFileUpload(): bool
    {
        return true;
    }

//    protected function prepareValue($value)
//    {
//        return trim(filter_var($value));//, FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_STRIP_LOW
//    }
}
