<?php

namespace App\Admin\Support\View\Form\Element;

use App\Admin\Support\Facades\Format;

class Enum extends BaseSelect
{
    protected array $options = [
        'enum'
    ];

    /**
     * @param class-string $enum
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

    protected function prepareValue($value)
    {
        if (is_object($value)) {
            $value = $value->value ?? $value->name;
        }

        return parent::prepareValue($value);
    }

    protected function getItems(): array
    {
        return array_map(function ($case): array {
            return [
                'value' => $case->value ?? $case->name,
                'text' => Format::enum($case)
            ];
        }, $this->enum::cases());
    }
}
