<?php

namespace App\Hotel\Support\View\Grid\Column;

use Gsdk\Grid\Support\AbstractColumn;

class Edit extends AbstractColumn
{
    public function formatValue($value, $row = null)
    {
        return '<i class="icon">edit</i>';
    }
}
