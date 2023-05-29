<?php

namespace Module\Services\NotificationManager\Providers;

use Module\Services\NotificationManager\Domain\Listener;
use Module\Services\NotificationManager\Domain\Notification;
use Sdk\Module\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        'Customer\Registered' => Listener\Customer\CustomerRegisteredListener::class
    ];

    protected array $eventNotifications = [
        'Customer\Registered' => Notification\Customer\CustomerRegistered::class
    ];
}
