<?php

namespace GTS\Shared\UI\Admin\View\Grid\Column;

use Gsdk\Grid\Column\Number;

class Id extends Number
{
    protected array $options = [
        'format' => 'id'
    ];
}
