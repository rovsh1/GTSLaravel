<?php

namespace App\Admin\Support\View\Form\Element;

use Gsdk\Form\Element\Select;

class Language extends Select
{
    public function __construct(string $name, array $options = [])
    {
        parent::__construct($name, $options);

        $this->setItems(app('languages'));
    }
}
