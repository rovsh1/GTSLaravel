<?php

namespace App\Admin\Support\View\Form\Element;

use Gsdk\Form\Support\Element\InputAttributes;

class LocaleTextarea extends LocaleText
{
    public function getLocaleInput($lang, $value): string
    {
        return '<textarea type="text"'
            . ' id="' . $this->getInputId() . '_' . $lang . '"'
            . ' name="' . $this->getInputName() . '[' . $lang . ']"'
            . (new InputAttributes($this))->renderWithoutName($this->attributes)
            . ' class="field-locale form-control"'
            . ' data-lang="' . $lang . '">' . $value . '</textarea>';
    }
}
