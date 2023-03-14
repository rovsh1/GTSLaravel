<?php

namespace App\Admin\Support\View\Form\Element;

use Gsdk\Form\Element\Select;

class EnumSelect extends Select
{
    /**
     * @param class-string $enum
     * @param string $name
     * @param array $options
     */
    public function __construct(string $enum, string $name, array $options = [])
    {
        parent::__construct($name, $options);

        $preparedCases = array_map(fn($enum) => ['id' => $enum->value, 'name' => $enum->name], $enum::cases());
        $this->setItems($preparedCases);
    }
}
