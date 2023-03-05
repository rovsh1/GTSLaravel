<?php

namespace App\Admin\Support\View\Grid;

use App\Admin\Support\View\Form\Form;

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
