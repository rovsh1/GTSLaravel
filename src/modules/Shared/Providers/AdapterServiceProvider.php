<?php

namespace Module\Shared\Providers;

use Illuminate\Support\ServiceProvider;
use Module\Shared\Contracts\Adapter\AirportAdapterInterface;
use Module\Shared\Contracts\Adapter\CityAdapterInterface;
use Module\Shared\Contracts\Adapter\CountryAdapterInterface;
use Module\Shared\Contracts\Adapter\CurrencyRateAdapterInterface;
use Module\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Module\Shared\Contracts\Adapter\RailwayStationAdapterInterface;
use Module\Shared\Infrastructure\Adapter\AirportAdapter;
use Module\Shared\Infrastructure\Adapter\CityAdapter;
use Module\Shared\Infrastructure\Adapter\CountryAdapter;
use Module\Shared\Infrastructure\Adapter\CurrencyRateAdapter;
use Module\Shared\Infrastructure\Adapter\FileStorageAdapter;
use Module\Shared\Infrastructure\Adapter\RailwayStationAdapter;

class AdapterServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(FileStorageAdapterInterface::class, FileStorageAdapter::class);
        $this->app->singleton(CurrencyRateAdapterInterface::class, CurrencyRateAdapter::class);
        $this->app->singleton(AirportAdapterInterface::class, AirportAdapter::class);
        $this->app->singleton(RailwayStationAdapterInterface::class, RailwayStationAdapter::class);
        $this->app->singleton(CountryAdapterInterface::class, CountryAdapter::class);
        $this->app->singleton(CityAdapterInterface::class, CityAdapter::class);
    }
}
