<?php

namespace Module\Pricing\Providers;

use Module\Pricing\Domain\Hotel\Repository\HotelRepositoryInterface;
use Module\Pricing\Domain\Hotel\Service\RoomPriceFinderInterface;
use Module\Pricing\Infrastructure\Repository\HotelRepository;
use Module\Pricing\Infrastructure\Service\RoomPriceFinder;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class HotelServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(HotelRepositoryInterface::class, HotelRepository::class);
        $this->app->singleton(RoomPriceFinderInterface::class, RoomPriceFinder::class);
    }
}
