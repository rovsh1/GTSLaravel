<?php

namespace App\Admin\Support\View\Grid\Column;

use Gsdk\Grid\Column\AbstractColumn;

class Edit extends AbstractColumn
{
    public function formatValue($value, $row = null)
    {
        return '<i class="icon">edit</i>';
    }
}
