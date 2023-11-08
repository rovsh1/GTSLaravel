<?php

namespace Module\Hotel\Moderation\Providers;

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
            \Module\Hotel\Moderation\Domain\Hotel\Repository\HotelRepositoryInterface::class,
            \Module\Hotel\Moderation\Infrastructure\Repository\HotelRepository::class
        );
        $this->app->singleton(
            \Module\Hotel\Moderation\Domain\Hotel\Repository\RoomRepositoryInterface::class,
            \Module\Hotel\Moderation\Infrastructure\Repository\RoomRepository::class
        );
        $this->app->singleton(
            \Module\Hotel\Moderation\Domain\Hotel\Repository\RoomQuotaRepositoryInterface::class,
            \Module\Hotel\Moderation\Infrastructure\Repository\RoomQuotaRepository::class
        );
        $this->app->singleton(
            \Module\Hotel\Moderation\Domain\Hotel\Repository\MarkupSettingsRepositoryInterface::class,
            \Module\Hotel\Moderation\Infrastructure\Repository\MarkupSettingsRepository::class
        );
        $this->app->singleton(
            \Module\Hotel\Moderation\Domain\Hotel\Repository\PriceRateRepositoryInterface::class,
            \Module\Hotel\Moderation\Infrastructure\Repository\PriceRateRepository::class
        );

        //@todo remove it
        $this->app->singleton(
            \Module\Hotel\Moderation\Domain\Hotel\Repository\RoomMarkupSettingsRepositoryInterface::class,
            \Module\Hotel\Moderation\Infrastructure\Repository\RoomMarkupSettingsRepository::class
        );
    }
}
