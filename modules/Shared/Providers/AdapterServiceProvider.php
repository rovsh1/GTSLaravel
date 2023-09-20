<?php

namespace Module\Shared\Providers;

use Illuminate\Support\ServiceProvider;
use Module\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Module\Shared\Domain\Adapter\CurrencyRateAdapterInterface;
use Module\Shared\Infrastructure\Adapter\CurrencyRateAdapter;
use Module\Shared\Support\Adapter\FileStorageAdapter;

class AdapterServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(FileStorageAdapterInterface::class, FileStorageAdapter::class);
        $this->app->singleton(CurrencyRateAdapterInterface::class, CurrencyRateAdapter::class);
    }
}
