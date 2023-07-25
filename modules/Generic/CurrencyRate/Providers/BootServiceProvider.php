<?php

namespace Module\Generic\CurrencyRate\Providers;

use Module\Generic\CurrencyRate\Domain\Adapter\LoggerAdapterInterface;
use Module\Generic\CurrencyRate\Domain\Repository\ApiRepositoryInterface;
use Module\Generic\CurrencyRate\Domain\Repository\CacheRepositoryInterface;
use Module\Generic\CurrencyRate\Domain\Repository\DatabaseRepositoryInterface;
use Module\Generic\CurrencyRate\Infrastructure\Adapter\LoggerAdapter;
use Module\Generic\CurrencyRate\Infrastructure\Repository\ApiRepository;
use Module\Generic\CurrencyRate\Infrastructure\Repository\CacheRepository;
use Module\Generic\CurrencyRate\Infrastructure\Repository\DatabaseRepository;
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
