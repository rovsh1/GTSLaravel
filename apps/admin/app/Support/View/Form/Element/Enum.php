<?php

namespace App\Admin\Support\View\Form\Element;

use Gsdk\Form\Element\Select;

class Enum extends Select
{
    /**
     * @var class-string $enumClass
     */
    private string $enumClass;

    /**
     * @param class-string $enumClass
     * @param string $name
     * @param array $options
     */
    public function __construct(string $enumClass, string $name, array $options = [])
    {
        parent::__construct($name, $options);
        $this->enumClass = $enumClass;

        $this->setItems(
            items: $this->getItems()
        );
    }

    private function getItems(): array
    {
        return array_map(function ($case): array {
            $prefix = $case::LANG_PREFIX;
            return [
                'id' => $case->value,
                'name' => __("{$prefix}.{$case->name}"),
            ];
        }, $this->enumClass::cases());
    }
}
