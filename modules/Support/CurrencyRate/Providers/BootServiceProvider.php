<?php

namespace Module\Support\CurrencyRate\Providers;

use Module\Support\CurrencyRate\Domain\Adapter\LoggerAdapterInterface;
use Module\Support\CurrencyRate\Domain\Repository\ApiRepositoryInterface;
use Module\Support\CurrencyRate\Domain\Repository\CacheRepositoryInterface;
use Module\Support\CurrencyRate\Domain\Repository\DatabaseRepositoryInterface;
use Module\Support\CurrencyRate\Infrastructure\Adapter\LoggerAdapter;
use Module\Support\CurrencyRate\Infrastructure\Repository\ApiRepository;
use Module\Support\CurrencyRate\Infrastructure\Repository\CacheRepository;
use Module\Support\CurrencyRate\Infrastructure\Repository\DatabaseRepository;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

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
