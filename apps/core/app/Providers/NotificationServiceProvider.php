<?php

namespace App\Core\Providers;

use Sdk\Module\Contracts\Notification\NotificationGatewayInterface;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;
use Sdk\Module\Notification\NotificationGateway;

class NotificationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(NotificationGatewayInterface::class, NotificationGateway::class);
    }
}
