<?php

namespace Gsdk\Form\Element;

class Number extends Input
{
    protected array $options = [
        'class' => 'form-control',
        'inputType' => 'number',
        'allowZero' => true,
        'nullToZero' => false,
        'fractionDigits' => 0,
        'nonnegative' => false
    ];

    protected array $attributes = ['readonly', 'required', 'disabled', 'min', 'max', 'step', 'autocomplete', 'list', 'pattern', 'placeholder', 'size'];

    public function checkValue($value): bool
    {
        $pv = $this->prepareValue($value);
        if ($pv === null) {
            return true;
        }

        if ($this->nonnegative && $pv < 0) {
            return false;
        }

        if (false === $this->allowZero && $pv == 0) {
            return false;
        }

        return parent::checkValue($pv);
    }

    protected function prepareValue($value)
    {
        if (self::isNullValue($value)) {
            return $this->nullToZero ? 0 : null;
        }

        if (is_string($value)) {
            $value = str_replace([',', ' '], '', $value);
        }

        return ($this->fractionDigits ? (float)$value : (int)$value);
    }

    public function isEmpty(): bool
    {
        return (0 !== $this->getValue() && parent::isEmpty());
    }

    private static function isNullValue($value): bool
    {
        return ('' === $value || null === $value);
    }
}
