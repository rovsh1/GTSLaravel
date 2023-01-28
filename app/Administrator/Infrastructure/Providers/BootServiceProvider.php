<?php

namespace GTS\Administrator\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

use GTS\Administrator\Domain;
use GTS\Administrator\Infrastructure;
use GTS\Administrator\Infrastructure\Facade;

class BootServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->registerInterfaces();
    }

    private function registerInterfaces()
    {
        $this->app->singleton(Facade\Reference\CountryFacadeInterface::class, Facade\Reference\CountryFacade::class);
        $this->app->singleton(Domain\Repository\CountryRepositoryInterface::class, Infrastructure\Repository\CountryRepository::class);

        $this->app->singleton(Facade\Reference\CurrencyFacadeInterface::class, Facade\Reference\CurrencyFacade::class);
    }
}
