<?php

namespace GTS\Services\Traveline\Infrastructure\Providers;

use GTS\Services\Traveline\Infrastructure\Adapter;
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
        $this->app->singleton(Adapter\Reservation\AdapterInterface::class, Adapter\Reservation\Adapter::class);
        $this->app->singleton(Facade\Hotel\FacadeInterface::class, Facade\Hotel\Facade::class);
        $this->app->singleton(Adapter\Hotel\AdapterInterface::class, Adapter\Hotel\Adapter::class);
        $this->app->singleton(Adapter\Traveline\AdapterInterface::class, function ($app) {
            $notificationsUrl = module('Traveline')->config('notifications_url');
            return new Adapter\Traveline\Adapter(app(ClientInterface::class), $notificationsUrl);
        });
    }
}
