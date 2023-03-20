<?php

namespace App\Admin\Support\View\Form\Element;

use Gsdk\Form\Element\Text;

class NumRange extends Text
{
    public function checkValue($value): bool
    {
        return is_array($value);
    }

    public function getHtml(): string
    {
        $values = $this->getValue();
        $inputName = $this->getInputName();
        $s = '<div class="input-group" id="' . $this->getInputId() . '">';
        $s .= '<input type="number"'
            . ' class="form-control"'
            . ' name="' . $inputName . '[valueFrom]"'
            . $this->_attr('min', 0, 0)
            . $this->_attr('placeholder', 0)
            . ' value="' . ($values ? $values['valueFrom'] : '') . '" />';
        $s .= '<input type="number"'
            . ' class="form-control"'
            . ' name="' . $inputName . '[valueTo]"'
            . $this->_attr('min', 1, 0)
            . $this->_attr('placeholder', 1)
            . ' value="' . ($values ? $values['valueTo'] : '') . '" />';
        $s .= '</div>';
        return $s;
    }

    protected function prepareValue($value)
    {
        $valueFrom = $this->_getValue($value, 'valueFrom');
        $valueTo = $this->_getValue($value, 'valueTo');
        if (null === $valueFrom && null === $valueTo)
            return null;

        if (null !== $valueTo && $valueFrom > $valueTo)
            $valueTo = $valueFrom;

        return [
            'valueFrom' => $valueFrom,
            'valueTo' => $valueTo
        ];
    }

    private function _getValue($value, $assoc)
    {
        if (!isset($value[$assoc]) || $value[$assoc] === '')
            return null;
        return (int)$value[$assoc];
    }

    private function _attr($name, $num, $default = false)
    {
        if (!$this->$name || !isset($this->$name[$num])) {
            if (false === $default)
                return '';

            return ' ' . $name . '="' . $default . '"';
        }
        return ' ' . $name . '="' . $this->$name[$num] . '"';
    }
}
