<?php

namespace Module\Shared\Providers;

use Custom\Framework\Foundation\Support\Providers\ServiceProvider;
use Module\Services\PortGateway\Client as PortGateway;
use Module\Shared\Infrastructure\Adapter\PortGatewayInterface;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->registerModules();

        $this->app->register(DateServiceProvider::class);

        $this->app->singleton(PortGatewayInterface::class, PortGateway::class);
        $this->app->alias(PortGatewayInterface::class, 'portGateway');
    }
}
