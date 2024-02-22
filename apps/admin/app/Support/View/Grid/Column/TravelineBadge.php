<?php

namespace App\Admin\Support\View\Grid\Column;

use Gsdk\Grid\Support\AbstractColumn;

class TravelineBadge extends AbstractColumn
{
    public function formatValue($value, $row = null)
    {
        if (!$value) {
            return '';
        }

        return '<span class="traveline-badge">TL</span>';
    }
}
