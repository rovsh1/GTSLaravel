<?php

namespace Gsdk\Form\Element;

use Gsdk\Form\Support\Element\InputAttributes;

class Textarea extends AbstractElement
{

    protected array $options = [
        'stripTags' => false,
        'unsafe' => true
    ];

    protected $attributes = ['placeholder', 'maxlength', 'required', 'autofocus', 'readonly'];

    protected function prepareValue($value)
    {
        if (null === $value) {
            return null;
        }

        if (is_scalar($value)) {
            $value = (string)$value;
        } else {
            return '';
        }

        if ($this->stripTags) {
            $value = strip_tags($value);
        }

        if ($this->unsafe) {
            $value = filter_var($value, FILTER_UNSAFE_RAW);
        }

        return trim($value);
    }

    public function getHtml(): string
    {
        return '<textarea name="' . $this->getInputName() . '"'
            . ' class="' . $this->class . '"'
            . ' id="' . $this->getInputId() . '"'
            . (new InputAttributes($this))->render() . '>'
            . $this->getValue()
            . '</textarea>';
    }

}
