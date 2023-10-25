<?php

namespace App\Admin\Support\View\Form\Element;

use Gsdk\Form\Element\Hidden;

class HiddenMultiSelect extends Hidden
{
    public function getHtml(): string
    {
        $value = is_array($this->getValue()) ? implode(',', $this->getValue()) : $this->getValue();
        return '<input type="hidden"'
            . ' id="' . $this->getInputId().'"'
            . ' name="' . $this->getInputName() . '[]"'
            . ($this->required ? ' required="required"' : '')
            . ' class="hidden-multiselect" '
            . ' multiple="multiple" '
            . ' value="' . $value . '">';
    }
}
