<?php

declare(strict_types=1);

namespace App\Admin\Support\View\Grid\Column;

use Gsdk\Grid\Column\AbstractColumn;

class BookingStatus extends AbstractColumn
{
    protected function init()
    {
        $this->statuses = collect($this->statuses)->keyBy('id');
    }

    public function formatValue($value, $row = null)
    {
        $status = $this->statuses->get($value->value);

        return "<span class='badge rounded-pill px-2 text-bg-{$status->color}'>{$status->name}</span>";
    }
}
