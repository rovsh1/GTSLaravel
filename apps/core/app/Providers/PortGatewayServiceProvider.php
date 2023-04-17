<?php

namespace App\Core\Providers;

use App\Core\Support\Adapters;
use Custom\Framework\Contracts\PortGateway\PortGatewayInterface;
use Custom\Framework\Foundation\Support\Providers\ServiceProvider;
use Custom\Framework\PortGateway\Client\Client as PortGateway;

class PortGatewayServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(PortGatewayInterface::class, PortGateway::class);
        $this->app->alias(PortGatewayInterface::class, 'portGateway');

        $this->registerAdapters();
    }

    private function registerAdapters(): void
    {
        $this->app->singleton('file-adapter', Adapters\FileAdapter::class);
        $this->app->singleton('mail-adapter', Adapters\MailAdapter::class);
        $this->app->singleton('notification-adapter', Adapters\NotificationAdapter::class);
        $this->app->singleton('currency-adapter', Adapters\CurrencyAdapter::class);
    }
}
