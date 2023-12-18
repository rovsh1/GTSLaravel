<?php

namespace App\Hotel\Support\View\Grid\Column;

use Gsdk\Grid\Column\Number;

class Id extends Number
{
    protected array $options = [
        'format' => 'id'
    ];
}
