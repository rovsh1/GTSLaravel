<?php

namespace App\Admin\Http\View\Grid;

use App\Admin\Http\View\Form\Form;

class Search extends Form
{
    protected function boot()
    {
        $this->method('get');
    }

    public function getData(): array
    {
        return array_filter(parent::getData());
    }
}
