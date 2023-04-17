<?php

namespace App\Admin\Support\View\Form\Element;

use Gsdk\Form\Element\Select;

class Enum extends Select
{
    protected array $options = [
        'enumClass'
    ];

    /**
     * @param class-string $enumClass
     * @param string $name
     * @param array $options
     */
    public function __construct(string $name, array $options = [])
    {
        parent::__construct($name, $options);

        $this->setItems(
            items: $this->getItems()
        );
    }

    private function getItems(): array
    {
        return array_map(function ($case): array {
            return [
                'id' => $case->value,
                'name' => $case->getLabel()
            ];
        }, $this->enumClass::cases());
    }
}
