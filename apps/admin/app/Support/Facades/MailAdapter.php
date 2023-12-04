<?php

namespace App\Admin\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static bool sendTo(string $to, string $subject, string $body)
 *
 * @see \App\Admin\Support\Adapters\MailAdapter
 */
class MailAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Admin\Support\Adapters\MailAdapter::class;
    }
}
