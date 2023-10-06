<?php

namespace Module\Booking\Providers\Shared;

use Module\Booking\Domain\Shared\Adapter\AdministratorAdapterInterface;
use Module\Booking\Domain\Shared\Adapter\ClientAdapterInterface;
use Module\Booking\Domain\Shared\Adapter\CountryAdapterInterface;
use Module\Booking\Infrastructure\Shared\Adapter\AdministratorAdapter;
use Module\Booking\Infrastructure\Shared\Adapter\ClientAdapter;
use Module\Booking\Infrastructure\Shared\Adapter\CountryAdapter;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(RepositoryServiceProvider::class);
    }

    public function boot()
    {
        $this->app->singleton(AdministratorAdapterInterface::class, AdministratorAdapter::class);
        $this->app->singleton(ClientAdapterInterface::class, ClientAdapter::class);
        $this->app->singleton(CountryAdapterInterface::class, CountryAdapter::class);
    }
}
