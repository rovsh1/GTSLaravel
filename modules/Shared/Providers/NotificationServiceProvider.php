<?php

namespace Module\Shared\Providers;

use Sdk\Module\Contracts\Notification\NotificationGatewayInterface;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;
use Sdk\Module\Notification\NotificationGateway;

class NotificationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(NotificationGatewayInterface::class, NotificationGateway::class);
    }
}
