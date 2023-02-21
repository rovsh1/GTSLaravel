<?php

namespace App\Core\Providers;

use Custom\Framework\Foundation\Support\Providers\ServiceProvider;
use Module\Services\PortGateway\Client as PortGateway;
use Module\Shared\Infrastructure\Adapter\PortGatewayInterface;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->app->registerModules();

        $this->app->register(DateServiceProvider::class);

        $this->app->singleton(PortGatewayInterface::class, PortGateway::class);
        $this->app->alias(PortGatewayInterface::class, 'portGateway');

        $namespace = $this->app->getNamespace();
        if ($namespace && class_exists($namespace . 'Providers\BootServiceProvider')) {
            $this->app->register($namespace . 'Providers\BootServiceProvider');
        }
    }
}
