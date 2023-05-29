<?php

namespace App\Core\Providers;

use App\Core\Support\Adapters;
use Sdk\Module\Contracts\PortGateway\PortGatewayInterface;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;
use Sdk\Module\PortGateway\Client\Client as PortGateway;

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
        $this->app->singleton('notification-adapter', Adapters\NotificationAdapter::class);
        $this->app->singleton('currency-adapter', Adapters\CurrencyAdapter::class);
    }
}
