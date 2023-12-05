<?php

namespace App\Hotel\Support\View\Grid;

use App\Hotel\Support\View\Form\FormBuilder;

class SearchForm extends FormBuilder
{
    protected function build()
    {
        $this->method('get');
    }

    public function getData(): array
    {
        return array_filter(parent::getData());
    }
}
