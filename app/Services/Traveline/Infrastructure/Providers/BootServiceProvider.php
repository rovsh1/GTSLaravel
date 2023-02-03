<?php

namespace GTS\Services\Traveline\Infrastructure\Providers;

use GTS\Services\Traveline\Domain;
use GTS\Services\Traveline\Infrastructure;
use GTS\Services\Traveline\Infrastructure\Facade;

use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function boot() {}

    public function register()
    {
        $this->registerInterfaces();
    }

    private function registerInterfaces()
    {
        $this->app->singleton(Facade\Reservation\FacadeInterface::class, Facade\Reservation\Facade::class);
        $this->app->singleton(Facade\Hotel\FacadeInterface::class, Facade\Hotel\Facade::class);

        $this->app->singleton(Domain\Adapter\Reservation\AdapterInterface::class, Infrastructure\Adapter\Reservation\Adapter::class);
        $this->app->singleton(Domain\Adapter\Hotel\AdapterInterface::class, Infrastructure\Adapter\Hotel\Adapter::class);
        $this->app->singleton(Domain\Adapter\Traveline\AdapterInterface::class, function ($app) {
            $notificationsUrl = module('Traveline')->config('notifications_url');
            return new Infrastructure\Adapter\Traveline\Adapter(app(ClientInterface::class), $notificationsUrl);
        });
    }
}
