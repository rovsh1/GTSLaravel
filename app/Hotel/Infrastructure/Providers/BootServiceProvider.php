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
        $this->app->singleton(Infrastructure\Api\Reservation\ApiInterface::class, Infrastructure\Api\Reservation\Api::class);

        $this->app->singleton(Domain\Repository\HotelRepositoryInterface::class, Infrastructure\Repository\HotelRepository::class);
    }
}
