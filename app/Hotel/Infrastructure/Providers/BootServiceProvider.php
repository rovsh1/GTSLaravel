<?php

namespace GTS\Hotel\Infrastructure\Providers;

use GTS\Hotel\Domain;
use GTS\Hotel\Infrastructure;

use Illuminate\Support\ServiceProvider;

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
        $this->app->singleton(Infrastructure\Facade\Reservation\FacadeInterface::class, Infrastructure\Facade\Reservation\Facade::class);
        $this->app->singleton(Infrastructure\Facade\Search\FacadeInterface::class, Infrastructure\Facade\Search\Facade::class);
        $this->app->singleton(Infrastructure\Facade\Info\FacadeInterface::class, Infrastructure\Facade\Info\Facade::class);
    }
}
