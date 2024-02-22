<?php

namespace App\Admin\Support\View\Form\Element;

class LocaleHidden extends LocaleText
{
    public function getLocaleInput($lang, $value): string
    {
        return '<input type="hidden"'
            . ' id="' . $this->getInputId() . '_' . $lang . '"'
            . ' name="' . $this->getInputName() . '[' . $lang . ']"'
            . ' class="field-locale"'
            . ' value="' . htmlspecialchars($value) . '" data-lang="' . $lang . '">';
    }
}
