<?php

namespace App\Admin\Support\View\Form\Element;

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
            . ' class="field-locale form-control"'
            . ' value="' . htmlspecialchars($value) . '" data-lang="' . $lang . '">';
    }

    public function getHtml(): string
    {
        $html = '';
        $value = $this->getValue();

        /** @var \App\Core\Components\Locale\Language $language */
        foreach (app('languages') as $language) {
            $html .= '<div class="field-locale-flag-wrapper m-auto">';
            $iconUrl = asset("/images/flag/{$language->code}.svg");
            $html .= "<img src='{$iconUrl}' alt='{$language->code}'/>";
            $html .= '</div>';

            $html .= '<div class="field-locale-input-wrapper">';
            $html .= $this->getLocaleInput($language->code, $value[$language->code] ?? null);
            $html .= '</div>';
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
