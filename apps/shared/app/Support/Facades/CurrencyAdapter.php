<?php

namespace App\Shared\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static float getRate(string $currency)
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
