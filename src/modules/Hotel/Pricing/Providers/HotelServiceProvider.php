<?php

namespace Module\Hotel\Pricing\Providers;

use Module\Hotel\Pricing\Domain\Hotel\Repository\HotelRepositoryInterface;
use Module\Hotel\Pricing\Domain\Hotel\Service\BaseDayValueFinderInterface;
use Module\Hotel\Pricing\Infrastructure\Repository\HotelRepository;
use Module\Hotel\Pricing\Infrastructure\Service\HotelRoomBaseDayValueFinder;
use Illuminate\Support\ServiceProvider;

class HotelServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(HotelRepositoryInterface::class, HotelRepository::class);
        $this->app->singleton(BaseDayValueFinderInterface::class, HotelRoomBaseDayValueFinder::class);
    }
}
