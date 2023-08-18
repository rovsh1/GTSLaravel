<?php

namespace Module\Shared\Providers;

use Module\Shared\Domain;
use Module\Shared\Infrastructure;
use Module\Shared\Support\Adapters\CurrencyRateAdapter;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(Domain\Adapter\CurrencyRateAdapterInterface::class, CurrencyRateAdapter::class);
    }
}
