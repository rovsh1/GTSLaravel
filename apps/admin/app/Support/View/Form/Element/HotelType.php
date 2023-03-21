<?php

namespace App\Admin\Support\View\Form\Element;

use App\Admin\Models\Hotel\Reference\Type as Model;
use Gsdk\Form\Element\Select;

class HotelType extends Select
{
    public function __construct(string $name, array $options = [])
    {
        parent::__construct($name, $options);

        $this->setItems(Model::orderBy('name')->get());
    }
}
