<?php

namespace Module\Generic\NotificationManager\Providers;

use Sdk\Module\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
    ];

    protected array $eventNotifications = [
//        'Customer\Registered' => CustomerRegistered::class
    ];
}
