<?php

namespace Module\Shared\Providers;

use Illuminate\Support\ServiceProvider;
use Module\Shared\Infrastructure\Adapter\AirportAdapter;
use Module\Shared\Infrastructure\Adapter\CityAdapter;
use Module\Shared\Infrastructure\Adapter\CountryAdapter;
use Module\Shared\Infrastructure\Adapter\CurrencyRateAdapter;
use Module\Shared\Infrastructure\Adapter\FileStorageAdapter;
use Module\Shared\Infrastructure\Adapter\RailwayStationAdapter;
use Sdk\Shared\Contracts\Adapter\AirportAdapterInterface;
use Sdk\Shared\Contracts\Adapter\CityAdapterInterface;
use Sdk\Shared\Contracts\Adapter\CountryAdapterInterface;
use Sdk\Shared\Contracts\Adapter\CurrencyRateAdapterInterface;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Sdk\Shared\Contracts\Adapter\RailwayStationAdapterInterface;

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
