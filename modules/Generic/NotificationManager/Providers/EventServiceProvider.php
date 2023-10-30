<?php

namespace Module\Generic\NotificationManager\Providers;

use Sdk\Module\Support\Providers\DomainEventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected array $listen = [];

    protected array $eventNotifications = [
//        'Customer\Registered' => CustomerRegistered::class
    ];
}
