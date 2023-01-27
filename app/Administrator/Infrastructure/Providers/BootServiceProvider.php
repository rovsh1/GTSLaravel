<?php

namespace GTS\Administrator\Infrastructure\Providers;

use GTS\Administrator\Infrastructure;

use Illuminate\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //$this->loadMigrationsFrom(__DIR__ . '/../Infrastructure/Database/Migrations');
    }

    public function register()
    {
        //$this->app->register(EventServiceProvider::class);
        //$this->app->addDeferredServices([EventServiceProvider::class]);

        $this->registerInterfaces();
    }

    private function registerInterfaces()
    {
        $this->app->singleton(Infrastructure\Facade\Reference\CurrencyFacadeInterface::class, Infrastructure\Facade\Reference\CurrencyFacade::class);
    }
}
