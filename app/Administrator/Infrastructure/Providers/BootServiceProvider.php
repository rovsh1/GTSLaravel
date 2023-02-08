<?php

namespace GTS\Administrator\Infrastructure\Providers;

use Custom\Framework\Foundation\Support\Providers\ServiceProvider;
use GTS\Administrator\Domain;
use GTS\Administrator\Infrastructure;
use GTS\Administrator\Infrastructure\Facade;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Facade\Reference\CountryFacadeInterface::class, Facade\Reference\CountryFacade::class);
        $this->app->singleton(Domain\Repository\CountryRepositoryInterface::class, Infrastructure\Repository\CountryRepository::class);

        $this->app->singleton(Facade\Reference\CurrencyFacadeInterface::class, Facade\Reference\CurrencyFacade::class);

        $this->app->singleton(Facade\FilesFacadeInterface::class, Facade\FilesFacade::class);
        $this->app->singleton(Domain\Adapter\FilesAdapterInterface::class, Infrastructure\Adapter\FilesAdapter::class);

        $this->app->register(AclServiceProvider::class);
    }
}
