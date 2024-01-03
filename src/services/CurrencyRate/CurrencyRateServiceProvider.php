<?php

namespace Services\CurrencyRate;

use Sdk\Module\Support\ServiceProvider;
use Sdk\Shared\Contracts\Adapter\CurrencyRateAdapterInterface;
use Services\CurrencyRate\Contracts\ApiRepositoryInterface;
use Services\CurrencyRate\Contracts\CacheRepositoryInterface;
use Services\CurrencyRate\Contracts\DatabaseRepositoryInterface;
use Services\CurrencyRate\Repository\ApiRepository;
use Services\CurrencyRate\Repository\CacheRepository;
use Services\CurrencyRate\Repository\DatabaseRepository;

class CurrencyRateServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(CurrencyRateAdapterInterface::class, CurrencyRateAdapter::class);

        $this->app->singleton(DatabaseRepositoryInterface::class, DatabaseRepository::class);
        $this->app->singleton(CacheRepositoryInterface::class, CacheRepository::class);
        $this->app->singleton(ApiRepositoryInterface::class, ApiRepository::class);
    }
}
