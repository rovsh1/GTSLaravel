<?php

namespace App\Hotel\Support\Format;

use Gsdk\Format\Rules\Number;

class PriceRule extends Number
{
    public function format($price, $format = null): string
    {
        return parent::format($price, 'price');
    }
}
