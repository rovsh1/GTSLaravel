<?php

namespace App\Admin\View\Form\Element;

use Gsdk\Form\Element\AbstractElement;

class LocaleText extends AbstractElement
{
    public function type(): string
    {
        return 'locale';
    }

    protected function prepareValue($value)
    {
        if (!is_array($value)) {
            return null;
        }

        $valueArr = [];
        foreach (app('languages') as $l) {
            $valueArr[$l->code] = $value[$l->code] ?? null;
        }

        return $valueArr;
    }

    public function getLocaleInput($lang, $value): string
    {
        return '<input type="text"'
            . ' id="' . $this->getInputId() . '_' . $lang . '"'
            . ' name="' . $this->getInputName() . '[' . $lang . ']"'
            . ' class="field-locale"'
            //. $this->attributes->withoutName()
            . ' value="' . htmlspecialchars($value) . '" data-lang="' . $lang . '">';
    }

    public function getHtml(): string
    {
        $html = '';
        $value = $this->getValue();

        foreach (app('languages') as $l) {
            $html .= $this->getLocaleInput($l->code, $value[$l->code] ?? null);
        }

        return $html;
    }

    public function isEmpty(): bool
    {
        if (empty($this->value)) {
            return true;
        }

        foreach ($this->value as $value) {
            if (!empty($value)) {
                return false;
            }
        }

        return true;
    }
}
