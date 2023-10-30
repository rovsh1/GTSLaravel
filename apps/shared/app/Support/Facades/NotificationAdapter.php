<?php

namespace App\Shared\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static bool sendTo(string $to, string $subject, string $body)
 */
class NotificationAdapter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Shared\Support\Adapters\MailAdapter::class;
    }
}
