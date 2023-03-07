<?php

namespace App\Admin\Support\View\Form;

class SearchForm extends Form
{
    protected function build()
    {
        parent::build();

        $this->method('GET');
    }
}
