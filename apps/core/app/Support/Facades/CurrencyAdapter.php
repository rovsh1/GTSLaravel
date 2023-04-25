<?php

namespace App\Core\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static float getRate(string $currency)
 * @method static void updateRates(\DateTime $date = null)
 * @method static void updateCountryRates(string $countryCode, \DateTime $date = null)
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
