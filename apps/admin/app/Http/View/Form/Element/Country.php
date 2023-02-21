<?php

namespace App\Admin\Http\View\Form\Element;

use Gsdk\Form\Element\Select;

class Country extends Select
{

    public function __construct(string $name, array $options = [])
    {
        parent::__construct($name, $options);

        $this->setItems($this->getCountries());
    }

    private function getCountries()
    {
        //TODO TEST! Replace to port
        return \GTS\Administrator\Infrastructure\Models\Country::joinTranslations()
            ->orderBy('name')
            ->get();
//        return app('portGateway')->request('administrator/search-countries', []);
    }
}
