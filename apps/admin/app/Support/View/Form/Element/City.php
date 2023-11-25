<?php

namespace App\Admin\Support\View\Form\Element;

use App\Admin\Models\Reference\City as Model;
use App\Admin\Models\Reference\Country;

class City extends BaseSelect
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
        $onlyWithAirports = $options['onlyWithAirports'] ?? false;
        if ($onlyWithAirports) {
            $countryQuery->whereHasAirport();
            $cityQuery->whereHasAirport();
        }

        $this->setGroups($countryQuery->get());
        $this->setItems($cityQuery->get());
    }
}
