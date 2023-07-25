<?php

namespace Module\Generic\NotificationManager\Providers;

use Module\Generic\NotificationManager\Domain\Listener\Customer\CustomerRegisteredListener;
use Module\Generic\NotificationManager\Domain\Notification\Customer\CustomerRegistered;
use Sdk\Module\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'Customer\Registered' => CustomerRegisteredListener::class
    ];

    protected array $eventNotifications = [
        'Customer\Registered' => CustomerRegistered::class
    ];
}
