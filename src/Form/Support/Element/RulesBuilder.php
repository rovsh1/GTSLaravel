<?php

namespace Gsdk\Form\Support\Element;

use Gsdk\Form\ElementInterface;

class RulesBuilder
{
    public function build(ElementInterface $element): array
    {
        $rules = [];

        if ($element->isRequired()) {
            $rules[] = 'required';
        }

        if ($element->nullable) {
            $rules[] = 'nullable';
        }

        if ($element->cast) {
            $rules[] = $element->cast;
        }

        if ($element->regex) {
            $rules[] = 'regex:' . $element->regex;
        }

        if ($element->min) {
            $rules[] = 'min:' . $element->min;
        }

        if ($element->max) {
            $rules[] = 'max:' . $element->max;
        }

        return $rules;
    }
}
