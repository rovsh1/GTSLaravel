<?php

namespace App\Admin\Support\View\Form\Element;

use App\Admin\Models\Reference\Currency as Model;

class Currency extends BaseSelect
{
    public function __construct(string $name, array $options = [])
    {
        $options['items'] = Model::orderBy('name')->get();

        if (!str_contains($name, '_id')) {
            $options['valueIndex'] = 'code_char';
        }

        parent::__construct($name, $options);

        $this->setValue($options['value'] ?? $options['default'] ?? null);
    }
}
