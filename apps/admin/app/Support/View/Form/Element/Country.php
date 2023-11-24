<?php

namespace App\Admin\Support\View\Form\Element;

use App\Admin\Models\Reference\Country as Model;

class Country extends BaseSelect
{
    private const UZBEKISTAN_COUNTRY_ID = 1;

    public function __construct(string $name, array $options = [])
    {
        parent::__construct($name, $options);

        $this->setItems(Model::whereId(self::UZBEKISTAN_COUNTRY_ID)->get());
    }

    public function getValue()
    {
        return self::UZBEKISTAN_COUNTRY_ID;
    }
}
