<?php

namespace App\Core\Providers;

use Custom\Framework\Contracts\Notification\NotificationGatewayInterface;
use Custom\Framework\Foundation\Support\Providers\ServiceProvider;
use Custom\Framework\Notification\NotificationGateway;

class NotificationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(NotificationGatewayInterface::class, NotificationGateway::class);
    }
}
