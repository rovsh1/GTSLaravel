<?php

namespace App\Admin\Support\View\Form\Element;

use App\Admin\Support\Facades\Format;
use Gsdk\Form\Element\Select;

class Enum extends Select
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

    private function getItems(): array
    {
        return array_map(function ($case): array {
            return [
                'value' => $case->value ?? $case->name,
                'text' => Format::enum($case)
            ];
        }, $this->enum::cases());
    }
}
