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

        $this->setGroups(Country::whereHasCity()->get());
        $this->setItems(Model::get());
    }
}
