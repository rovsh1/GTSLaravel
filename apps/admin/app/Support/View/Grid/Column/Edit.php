<?php

namespace App\Admin\Support\View\Grid\Column;

use Gsdk\Grid\Column\AbstractColumn;

class Edit extends AbstractColumn
{
    protected array $options = [
        'route' => null
    ];

    public function renderer($row, $value): string
    {
        return '<a href="' . route($this->route, $row->id) . '"><i class="icon">edit</i></a>';
    }
}
