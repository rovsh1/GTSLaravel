<?php

namespace Module\Pricing\CurrencyRate\Providers;

use Custom\Framework\Foundation\Support\Providers\ServiceProvider;
use Module\Pricing\CurrencyRate\Domain\Adapter\LoggerAdapterInterface;
use Module\Pricing\CurrencyRate\Domain\Repository\ApiRepositoryInterface;
use Module\Pricing\CurrencyRate\Domain\Repository\CacheRepositoryInterface;
use Module\Pricing\CurrencyRate\Domain\Repository\DatabaseRepositoryInterface;
use Module\Pricing\CurrencyRate\Infrastructure\Adapter\LoggerAdapter;
use Module\Pricing\CurrencyRate\Infrastructure\Repository\ApiRepository;
use Module\Pricing\CurrencyRate\Infrastructure\Repository\CacheRepository;
use Module\Pricing\CurrencyRate\Infrastructure\Repository\DatabaseRepository;

class BootServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(DatabaseRepositoryInterface::class, DatabaseRepository::class);
        $this->app->singleton(CacheRepositoryInterface::class, CacheRepository::class);
        $this->app->singleton(ApiRepositoryInterface::class, ApiRepository::class);

        $this->app->singleton(LoggerAdapterInterface::class, LoggerAdapter::class);
    }
}
