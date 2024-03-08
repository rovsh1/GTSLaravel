<?php

declare(strict_types=1);

namespace App\Admin\Support\View\Grid\Column;

use Gsdk\Grid\Support\AbstractColumn;

class SupplierPaymentStatus extends AbstractColumn
{
    public function formatValue($value, $row = null)
    {
        if ($value) {
            return "<span class='badge rounded-pill px-2 text-bg-success'>Оплачен</span>";
        }
        return "<span class='badge rounded-pill px-2 text-bg-warning'>Не оплачен</span>";
    }
}
