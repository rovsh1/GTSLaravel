<?php

namespace GTS\Shared\Infrastructure\Providers;

use GTS\Services\PortGateway\Infrastructure\PortGateway;
use GTS\Shared\Infrastructure\Adapter\PortGatewayInterface;
use GTS\Shared\Infrastructure\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->registerModules();

        $this->app->singleton(PortGatewayInterface::class, PortGateway::class);
    }
}
