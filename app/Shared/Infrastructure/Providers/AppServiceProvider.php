<?php

namespace GTS\Shared\Infrastructure\Providers;

use Custom\Framework\Foundation\Support\Providers\ServiceProvider;
use GTS\Services\PortGateway\Client as PortGateway;
use GTS\Shared\Infrastructure\Adapter\PortGatewayInterface;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->registerModules();

        $this->app->singleton(PortGatewayInterface::class, PortGateway::class);
        $this->app->alias(PortGatewayInterface::class, 'portGateway');
    }
}
