<?php

namespace Gsdk\Form\Element;

use Gsdk\Form\Support\Element\InputAttributes;

class Input extends AbstractElement
{
    protected array $options = [
        'inputType' => 'text'
    ];

    protected array $attributes = ['readonly', 'required', 'disabled'];

    public function type(): string
    {
        return $this->inputType ?? 'text';
    }

    public function getHtml(): string
    {
        return '<input'
            . ' type="' . ($this->inputType ?? 'text') . '"'
            . ' class="' . ($this->class ?? 'input-' . $this->inputType) . '"'
            . $this->getListAttribute()
            . (new InputAttributes($this))->render($this->attributes)
            . ' value="' . self::escape($this->getValue()) . '">'
            . $this->getListHtml();
    }

    private function getListAttribute(): string
    {
        if (empty($this->list)) {
            return '';
        }

        return ' list="' . (is_string($this->list) ? $this->list : $this->getDatalistId()) . '"';
    }

    private function getListHtml(): string
    {
        if (!is_iterable($this->list)) {
            return '';
        }

        $html = '<datalist id="' . $this->getDatalistId() . '">';
        foreach ($this->list as $s) {
            $html .= '<option value="' . $s . '">';
        }
        $html .= '</datalist>';

        return $html;
    }

    private function getDatalistId(): string
    {
        return $this->getInputId() . '_datalist';
    }
}

/*<input type="button">
<input type="checkbox">
<input type="color">
<input type="date">
<input type="datetime-local">
<input type="email">
<input type="file">
<input type="hidden">
<input type="image">
<input type="month">
<input type="number">
<input type="password"> ['autocomplete', 'maxlength', 'minlength', 'pattern', 'placeholder', 'readonly', 'required', 'size']
<input type="radio">
<input type="range"> ['autocomplete', 'list', 'max', 'min', 'step'];
<input type="reset">
<input type="search">
<input type="submit">
<input type="tel"> ['autocomplete', 'maxlength', 'minlength', 'pattern', 'placeholder', 'readonly', 'required', 'size']
<input type="text">
<input type="time"> ['autocomplete', 'list', 'readonly', 'step']
<input type="url">
<input type="week">*/
