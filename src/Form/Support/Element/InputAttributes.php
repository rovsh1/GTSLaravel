<?php

namespace Gsdk\Form\Support\Element;

use Gsdk\Form\ElementInterface;

class InputAttributes
{
    private static array $attributesCasts = [
        'autofocus' => 'attr',
        'checked' => 'attr',
        'disabled' => 'attr',
        'readonly' => 'attr',
        'multiple' => 'attr',
        'required' => 'attr',
        'spellcheck' => 'bool',
        'minlength' => 'int',
        'maxlength' => 'int',
        'min' => 'int',
        'max' => 'int',
        'size' => 'int',
        'step' => 'int',
        'tabindex' => 'int',
        'inputmode' => 'text',
        'pattern' => 'text',
        'placeholder' => 'text',
    ];

    public function __construct(private readonly ElementInterface $element) {}

    public function render(): string
    {
        $s = '';
        foreach (self::$attributesCasts as $k => $cast) {
            $s .= $this->cast($this->element->$k, $cast, $k);
        }

        if (($v = $this->element->autocomplete)) {
            $s .= ' autocomplete="' . (is_string($v) ? $v : ($v ? 'on' : 'off')) . '"';
        }

        return $s;
    }

    private function cast($value, $cast, $name): string
    {
        if (null === $value) {
            return '';
        } elseif ($cast === 'attr') {
            return $value ? ' ' . $name : '';
        }

        $attr = match ($cast) {
            'bool' => ($name ? 'true' : 'false'),
            'int' => (int)$value,
            default => $value,
        };

        return ' ' . $name . '="' . $attr . '"';
    }
}
