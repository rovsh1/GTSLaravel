<?php

namespace GTS\Hotel\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

use GTS\Hotel\Infrastructure\Api;

class BootServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //$this->loadMigrationsFrom(__DIR__ . '/../Infrastructure/Database/Migrations');
    }

    public function register()
    {
        //$this->app->register(EventServiceProvider::class);
        //$this->app->addDeferredServices([EventServiceProvider::class]);

        $this->registerInterfaces();
    }

    private function registerInterfaces()
    {
        $this->app->singleton(Api\Reservation\ApiInterface::class, Api\Reservation\Api::class);
        //$this->app->singleton(RequestRepositoryInterface::class, RequestRepository::class);
        //$this->app->singleton(RequestFactoryInterface::class, RequestFactory::class);
    }
}
