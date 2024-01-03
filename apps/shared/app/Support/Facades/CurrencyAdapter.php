<?php

namespace App\Shared\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static float getRate(string $currency, string $country = null)
 *
 * @see \App\Shared\Support\Adapters\CurrencyAdapter
 */
class CurrencyAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Shared\Support\Adapters\CurrencyAdapter::class;
    }
}
