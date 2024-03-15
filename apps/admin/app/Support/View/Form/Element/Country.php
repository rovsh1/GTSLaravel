<?php

namespace App\Admin\Support\View\Form\Element;

use App\Admin\Models\Reference\Country as Model;
use Gsdk\Form\Element\Select;

class Country extends Select
{
    private const UZBEKISTAN_COUNTRY_ID = 1;

    public function __construct(string $name, array $options = [])
    {
        parent::__construct($name, $options);

        $this->setItems(Model::onlyVisibleForLists()->get());
    }

    public function getValue()
    {
        return self::UZBEKISTAN_COUNTRY_ID;
    }
}
