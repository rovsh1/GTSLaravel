<?php

namespace App\Admin\Support\View\Form\Element;

use App\Admin\Models\Administrator\Administrator as Model;

class Manager extends BaseSelect
{
    public function __construct(string $name, array $options = [])
    {
        parent::__construct($name, $options);

        $this->setItems(Model::orderBy('name')->get());
        $this->setValue($options['value'] ?? $options['default'] ?? null);
    }
}
