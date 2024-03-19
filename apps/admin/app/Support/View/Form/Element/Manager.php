<?php

namespace App\Admin\Support\View\Form\Element;

use App\Admin\Models\Administrator\Administrator as Model;
use Gsdk\Form\Element\Select;

class Manager extends Select
{
    public function __construct(string $name, array $options = [])
    {
        parent::__construct($name, $options);

        $this->setItems(Model::orderBy('presentation')->get());
        $this->setValue($options['value'] ?? $options['default'] ?? null);
    }
}
