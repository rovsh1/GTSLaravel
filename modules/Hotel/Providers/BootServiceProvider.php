<?php

namespace Module\Hotel\Providers;

use Module\Hotel\Domain;
use Module\Hotel\Infrastructure;

class BootServiceProvider extends \Sdk\Module\Foundation\Support\Providers\ServiceProvider
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
            Domain\Repository\HotelRepositoryInterface::class,
            Infrastructure\Repository\HotelRepository::class
        );
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
        $this->app->singleton(
            Domain\Repository\RoomMarkupSettingsRepositoryInterface::class,
            Infrastructure\Repository\RoomMarkupSettingsRepository::class
        );
    }
}
