<?php

namespace App\Admin\Support\View\Form\Element;

use App\Admin\Models\Reference\City as Model;
use App\Admin\Models\Reference\Country;
use Gsdk\Form\Element\Select;

class City extends Select
{
    public function __construct(string $name, array $options = [])
    {
        $options['groupIndex'] = 'country_id';

        parent::__construct($name, $options);

        $onlyWithHotels = $options['onlyWithHotels'] ?? false;
        $countryQuery = Country::whereHasCity();
        $cityQuery = Model::query();
        if ($onlyWithHotels) {
            $countryQuery->whereHasHotel();
            $cityQuery->whereHasHotel();
        }
        $this->setGroups($countryQuery->get());
        $this->setItems($cityQuery->get());
    }
}
