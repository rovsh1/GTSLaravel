<?php

namespace App\Admin\Http\View\Form\Element;

use Gsdk\Form\Element\Select;

use App\Admin\Models\Currency as Model;

class Currency extends Select
{
    public function __construct(string $name, array $options = [])
    {
        parent::__construct($name, $options);

        $this->setItems(Model::orderBy('name')->get());
    }
}
