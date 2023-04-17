<?php

namespace App\Core\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static bool sendTo(string $to, string $subject, string $body)
 *
 * @see \App\Core\Support\Adapters\MailAdapter
 */
class CurrencyAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'currency-adapter';
    }
}
