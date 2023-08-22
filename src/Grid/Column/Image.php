<?php

namespace Gsdk\Grid\Column;

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
