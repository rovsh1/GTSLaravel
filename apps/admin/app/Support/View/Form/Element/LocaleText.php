<?php

namespace App\Admin\Support\View\Form\Element;

use App\Admin\Support\Facades\Languages;
use Gsdk\Form\Element\AbstractElement;
use Gsdk\Form\Support\Element\InputAttributes;

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

        $valueArray = [];
        /** @var \App\Core\Components\Locale\Language $language */
        foreach (Languages::all() as $language) {
            $valueArray[$language->code] = $value[$language->code] ?? null;
        }

        return $valueArray;
    }

    public function getLocaleInput($lang, $value): string
    {
        return '<input type="text"'
            . ' id="' . $this->getInputId() . '_' . $lang . '"'
            . ' name="' . $this->getInputName() . '[' . $lang . ']"'
            . (new InputAttributes($this))->renderWithoutName($this->attributes)
            . ' class="field-locale form-control"'
            . ' value="' . htmlspecialchars($value) . '" data-lang="' . $lang . '">';
    }

    public function getHtml(): string
    {
        $html = '';
        $value = $this->getValue();

        /** @var \App\Core\Components\Locale\Language $language */
        $html .= '<div class="field-locale-inputs-wrapper">';
        foreach (Languages::all() as $language) {
            $html .= '<div class="field-locale-input-wrapper">';
            $iconUrl = asset("/images/flag/{$language->code}.svg");
            $html .= "<img src='{$iconUrl}' alt='{$language->code}'/>";
            $html .= $this->getLocaleInput($language->code, $value[$language->code] ?? null);
            $html .= '</div>';
        }
        $html .= '</div>';

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

    public function rules(): array|string
    {
        $valueRules = parent::rules();
        $rules = [];
        /** @var \App\Core\Components\Locale\Language $language */
        foreach (Languages::all() as $language) {
            $rules[$this->name . '.' . $language->code] = $valueRules;
        }
        return $rules;
    }
}
