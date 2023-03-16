<?php

namespace App\Admin\Support\View\Form\Element;

use App\Admin\Models\Client\Client as Model;
use Gsdk\Form\Element\Select;

class Client extends Select
{
    public function __construct(string $name, array $options = [])
    {
        parent::__construct($name, $options);

        $this->setItems(Model::orderBy('name')->get());
    }
}
