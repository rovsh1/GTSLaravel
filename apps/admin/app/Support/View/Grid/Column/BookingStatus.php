<?php

declare(strict_types=1);

namespace App\Admin\Support\View\Grid\Column;

use Gsdk\Grid\Column\AbstractColumn;

class BookingStatus extends AbstractColumn
{
    protected function init()
    {
        $this->statuses = collect($this->statuses)->keyBy('id')->map->name;
    }

    public function formatValue($value, $row = null)
    {
        return $this->statuses->get($value->value);
    }
}
