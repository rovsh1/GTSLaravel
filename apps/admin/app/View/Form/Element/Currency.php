<?php

namespace App\Admin\View\Form\Element;

use App\Admin\Models\Currency as Model;
use Gsdk\Form\Element\Select;

class Currency extends Select
{
    public function __construct(string $name, array $options = [])
    {
        parent::__construct($name, $options);

        $this->setItems(Model::orderBy('name')->get());
    }
}
