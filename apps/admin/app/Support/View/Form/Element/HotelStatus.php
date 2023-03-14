<?php

namespace App\Admin\Support\View\Form\Element;

use Gsdk\Form\Element\Select;

class HotelStatus extends Select
{
    public function __construct(string $name, array $options = [])
    {
        parent::__construct($name, $options);

        $this->setItems(
            items: $this->getItems()
        );
    }

    private function getItems(): array
    {
        return [
            ['id' => 1, 'name' => 'Черновик'],
            ['id' => 2, 'name' => 'Опубликован'],
            ['id' => 3, 'name' => 'Заблокирован'],
            ['id' => 4, 'name' => 'Архив'],
        ];
    }
}
