<?php

namespace App\Hotel\Support\View\Grid\Column;

use Gsdk\Grid\Support\AbstractColumn;
use Illuminate\Support\Facades\Storage;

class Image extends AbstractColumn
{
    public function formatValue($value, $row = null)
    {
        if ($value) {
            return '<img src="' . Storage::disk('file')->url($value) . '" alt="">';
        }

        return '';
    }
}
