<?php

namespace GTS\Services\Traveline\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

use GTS\Services\Traveline\Infrastructure\Adapter;
use GTS\Services\Traveline\Infrastructure\Facade;

class BootServiceProvider extends ServiceProvider
{
    public function boot() {}

    public function register()
    {
        $this->registerInterfaces();
    }

    private function registerInterfaces()
    {
        $this->app->singleton(Facade\Reservation\FacadeInterface::class, Facade\Reservation\Facade::class);
        $this->app->singleton(Adapter\Reservation\AdapterInterface::class, Adapter\Reservation\Adapter::class);
        $this->app->singleton(Facade\Hotel\FacadeInterface::class, Facade\Hotel\Facade::class);
        $this->app->singleton(Adapter\Hotel\AdapterInterface::class, Adapter\Hotel\Adapter::class);
        $this->app->singleton(Adapter\Traveline\AdapterInterface::class, Adapter\Traveline\Adapter::class);
    }
}
