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
    }

    public function boot()
    {
        $this->registerInterfaces();
        //$this->loadMigrationsFrom(__DIR__ . '/../Infrastructure/Database/Migrations');
    }

    private function registerInterfaces()
    {
//        $this->app->singleton(Domain\Repository\RoomQuotaRepositoryInterface::class, Infrastructure\Repository\RoomQuotaRepository::class);
        $this->app->singleton(
            Domain\Repository\RoomRepositoryInterface::class,
            Infrastructure\Repository\RoomRepository::class
        );
        $this->app->singleton(
            Domain\Repository\RoomQuotaRepositoryInterface::class,
            Infrastructure\Repository\RoomQuotaRepository::class
        );
        $this->app->singleton(
            Domain\Repository\MarkupSettingsRepositoryInterface::class,
            Infrastructure\Repository\MarkupSettingsRepository::class
        );
    }
}
