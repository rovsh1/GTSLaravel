<?php

namespace App\Admin\Support\View\Grid\Column;

use Gsdk\Grid\Column\Number;

class Price extends Number
{
    protected array $options = [
        'format' => 'price'
    ];

    public function renderer($row, $value)
    {
        $priceFormatted = $this->formatValue($value);
        dd($row, $priceFormatted);
        if (!$value) {
            return '';
        }
        if ($this->currencyIndex) {
            $cv = $row->{$this->currencyIndex};
        } elseif ($this->currency) {
            $cv = $this->currency;
        } else {
            $cv = call_user_func(['\\' . $this->enum, 'getDefault']);
        }

        return parent::render($value, $row)
            . ' <span>' . call_user_func(['\\' . $this->enum, 'getLabel'], $cv) . '</span>';
    }
}
