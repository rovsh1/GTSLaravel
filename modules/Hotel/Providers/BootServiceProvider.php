<?php

namespace Module\Hotel\Providers;

use Custom\Framework\Foundation\Support\Providers\ServiceProvider;
use Module\Hotel\Domain;
use Module\Hotel\Infrastructure;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(EventServiceProvider::class);
        //$this->app->addDeferredServices([EventServiceProvider::class]);

        $this->registerInterfaces();
    }

    public function boot()
    {
        //$this->loadMigrationsFrom(__DIR__ . '/../Infrastructure/Database/Migrations');
    }

    private function registerInterfaces()
    {
//        $this->app->singleton(Domain\Repository\RoomQuotaRepositoryInterface::class, Infrastructure\Repository\RoomQuotaRepository::class);
        $this->app->singleton(Domain\Repository\RoomRepositoryInterface::class, Infrastructure\Repository\RoomRepository::class);
        $this->app->singleton(Domain\Repository\RoomQuotaRepositoryInterface::class, Infrastructure\Repository\RoomQuotaRepository::class);
        $this->app->singleton(Domain\Repository\AdditionalConditionsRepositoryInterface::class, Infrastructure\Repository\AdditionalConditionsRepository::class);
    }
}
