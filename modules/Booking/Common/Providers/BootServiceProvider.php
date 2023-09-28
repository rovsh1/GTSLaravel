<?php

namespace Module\Booking\Common\Providers;

use Module\Booking\Common\Domain\Adapter\AdministratorAdapterInterface;
use Module\Booking\Common\Domain\Adapter\ClientAdapterInterface;
use Module\Booking\Common\Domain\Adapter\CountryAdapterInterface;
use Module\Booking\Common\Infrastructure\Adapter\AdministratorAdapter;
use Module\Booking\Common\Infrastructure\Adapter\ClientAdapter;
use Module\Booking\Common\Infrastructure\Adapter\CountryAdapter;
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
