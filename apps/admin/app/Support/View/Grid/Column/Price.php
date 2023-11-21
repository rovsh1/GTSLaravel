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
        $priceFormatted = $this->formatValue($value);
//        return $priceFormatted . ' <span>' . call_user_func(['\\' . $this->enum, 'getLabel'], $cv) . '</span>';
        return $priceFormatted . ' <span>' .  $cv->name . '</span>';
    }
}
