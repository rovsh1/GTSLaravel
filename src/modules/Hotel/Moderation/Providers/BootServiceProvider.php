<?php

namespace Module\Hotel\Moderation\Providers;

use Module\Hotel\Moderation\Domain\Hotel\Repository\HotelRepositoryInterface;
use Module\Hotel\Moderation\Domain\Hotel\Repository\MarkupSettingsRepositoryInterface;
use Module\Hotel\Moderation\Domain\Hotel\Repository\PriceRateRepositoryInterface;
use Module\Hotel\Moderation\Domain\Hotel\Repository\RoomMarkupSettingsRepositoryInterface;
use Module\Hotel\Moderation\Domain\Hotel\Repository\RoomRepositoryInterface;
use Module\Hotel\Moderation\Infrastructure\Repository\HotelRepository;
use Module\Hotel\Moderation\Infrastructure\Repository\MarkupSettingsRepository;
use Module\Hotel\Moderation\Infrastructure\Repository\PriceRateRepository;
use Module\Hotel\Moderation\Infrastructure\Repository\RoomMarkupSettingsRepository;
use Module\Hotel\Moderation\Infrastructure\Repository\RoomRepository;
use Illuminate\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
        //$this->app->addDeferredServices([EventServiceProvider::class]);
    }

    public function boot(): void
    {
        $this->app->singleton(HotelRepositoryInterface::class, HotelRepository::class);
        $this->app->singleton(RoomRepositoryInterface::class, RoomRepository::class);
        $this->app->singleton(MarkupSettingsRepositoryInterface::class, MarkupSettingsRepository::class);
        $this->app->singleton(PriceRateRepositoryInterface::class, PriceRateRepository::class);

        //@todo remove it
        $this->app->singleton(RoomMarkupSettingsRepositoryInterface::class, RoomMarkupSettingsRepository::class);
    }
}
