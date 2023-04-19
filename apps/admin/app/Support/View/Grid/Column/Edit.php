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
        if (is_callable($this->route)) {
            $url = call_user_func($this->route, $row);
        } else {
            $url = route($this->route, $row->id);
        }
        return '<a href="' . $url . '"><i class="icon">edit</i></a>';
    }
}
