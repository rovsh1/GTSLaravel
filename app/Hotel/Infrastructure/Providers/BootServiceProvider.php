<?php

namespace GTS\Hotel\Infrastructure\Providers;

use Custom\Framework\Foundation\Support\Providers\ServiceProvider;
use GTS\Hotel\Domain;
use GTS\Hotel\Infrastructure;

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
        $this->app->singleton(Infrastructure\Facade\ReservationFacadeInterface::class, Infrastructure\Facade\ReservationReservationFacade::class);
        $this->app->singleton(Infrastructure\Facade\SearchFacadeInterface::class, Infrastructure\Facade\SearchFacade::class);
        $this->app->singleton(Infrastructure\Facade\InfoFacadeInterface::class, Infrastructure\Facade\InfoFacade::class);
    }
}
