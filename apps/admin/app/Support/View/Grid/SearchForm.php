<?php

namespace App\Admin\Support\View\Grid;

use App\Admin\Support\View\Form\Form;

class SearchForm extends Form
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
