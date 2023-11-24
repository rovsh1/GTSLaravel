<?php

declare(strict_types=1);

namespace App\Admin\Support\View\Form\Element;

use Gsdk\Form\Element\Select;

class BaseSelect extends Select
{
    public function __construct(string $name, array $options = [])
    {
        parent::__construct($name, $options);

        $this->class .= ' form-select-element';
    }
}
