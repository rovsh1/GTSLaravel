<?php

namespace App\Admin\Support\View\Grid\Column;

use App\Admin\Support\Facades\Format;
use Gsdk\Grid\Column\AbstractColumn;

class Enum extends AbstractColumn
{
    public function formatValue($value, $row = null)
    {
        return Format::enum(is_object($value) ? $value : ($this->enum)::tryFrom($value));
    }
}
