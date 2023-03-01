<?php

namespace App\Admin\View\Form\Element;

class LocaleTextarea extends LocaleText
{
    public function getLocaleInput($lang, $value): string
    {
        return '<textarea '
            . ' id="' . $this->getInputId() . '_' . $lang . '"'
            . ' name="' . $this->getInputName() . '[' . $lang . ']"'
            . ' class="field-locale"'
            . ' data-lang="' . $lang . '">' . $value . '</textarea>';
    }
}
