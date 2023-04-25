<?php

namespace App\Core\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static float getRate(string $currency)
 *
 * @see \App\Core\Support\Adapters\CurrencyAdapter
 */
class CurrencyAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'currency-adapter';
    }
}
