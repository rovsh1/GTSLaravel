<?php

namespace App\Core\Providers;

use Custom\Framework\Foundation\Support\Providers\ServiceProvider;
use Module\Services\PortGateway\Client as PortGateway;
use Module\Shared\Infrastructure\Adapter\PortGatewayInterface;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerServices();

        $this->app->register(EventServiceProvider::class);
        $this->app->register(ModulesServiceProvider::class);
        $this->app->register(DateServiceProvider::class);

        $this->app->singleton(PortGatewayInterface::class, PortGateway::class);
        $this->app->alias(PortGatewayInterface::class, 'portGateway');

        $this->registerApp();
    }

    private function registerServices()
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
        $this->app->register(\Spatie\LaravelData\LaravelDataServiceProvider::class);
    }

    private function registerApp()
    {
        $namespace = $this->app->getNamespace();
        if ($namespace && class_exists($namespace . 'Providers\BootServiceProvider')) {
            $this->app->register($namespace . 'Providers\BootServiceProvider');
        }
    }
}
