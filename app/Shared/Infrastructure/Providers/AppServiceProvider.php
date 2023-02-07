<?php

namespace GTS\Shared\Infrastructure\Providers;

use Custom\Framework\Foundation\Support\ServiceProvider;
use Custom\Framework\Port\PortGatewayInterface;
use GTS\Services\PortGateway\Infrastructure\Client\Client;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->registerModules();

        $this->app->singleton(PortGatewayInterface::class, Client::class);
    }
}
