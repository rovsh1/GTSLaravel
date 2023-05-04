<?php

namespace App\Admin\Support\View\Grid\Column;

use App\Admin\Support\Facades\Languages;
use Gsdk\Grid\Column\AbstractColumn;

class Language extends AbstractColumn
{
    public function formatValue($value, $row = null)
    {
        if (null === $value) {
            return '';
        }

        $language = Languages::get($value);

        return '<img src="/images/flag/' . $language->code . '.svg"'
            . ' title="' . $language->name . '" alt="' . $language->code . '">'//. $language->name
            ;
    }
}