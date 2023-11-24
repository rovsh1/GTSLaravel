<?php

namespace App\Admin\Support\View\Form\Element;

use App\Admin\Models\Hotel\Hotel as Model;
use App\Admin\Models\Reference\City;

class Hotel extends BaseSelect
{
    public function __construct(string $name, array $options = [])
    {
        $options['groupIndex'] = 'city_id';

        parent::__construct($name, $options);

        $this->setGroups(City::whereHasHotel()->get());
        $this->setItems(Model::get());
    }
}
