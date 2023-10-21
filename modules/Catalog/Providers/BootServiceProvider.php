<?php

namespace Module\Catalog\Providers;

use Sdk\Module\Support\ServiceProvider;

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
            \Module\Catalog\Domain\Hotel\Repository\HotelRepositoryInterface::class,
            \Module\Catalog\Infrastructure\Repository\HotelRepository::class
        );
        $this->app->singleton(
            \Module\Catalog\Domain\Hotel\Repository\RoomRepositoryInterface::class,
            \Module\Catalog\Infrastructure\Repository\RoomRepository::class
        );
        $this->app->singleton(
            \Module\Catalog\Domain\Hotel\Repository\RoomQuotaRepositoryInterface::class,
            \Module\Catalog\Infrastructure\Repository\RoomQuotaRepository::class
        );
        $this->app->singleton(
            \Module\Catalog\Domain\Hotel\Repository\MarkupSettingsRepositoryInterface::class,
            \Module\Catalog\Infrastructure\Repository\MarkupSettingsRepository::class
        );

        //@todo remove it
        $this->app->singleton(
            \Module\Catalog\Domain\Hotel\Repository\RoomMarkupSettingsRepositoryInterface::class,
            \Module\Catalog\Infrastructure\Repository\RoomMarkupSettingsRepository::class
        );
    }
}
