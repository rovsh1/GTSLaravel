<?php

namespace App\Admin\Support\View\Form\Element;

use App\Admin\Models\Reference\Country;
use App\Admin\Models\Reference\City as Model;
use Gsdk\Form\Element\Select;

class City extends Select
{
    public function __construct(string $name, array $options = [])
    {
        $options['groupIndex'] = 'country_id';

        parent::__construct($name, $options);

        //@todo добавить настройку, по которой буду вытаскивать только те города/страны где есть отели. Добавить whereHasHotel в страну
        $this->setGroups(Country::whereHasCity()->get());
        $this->setItems(Model::whereHasHotel()->get());
    }
}
