<?php

namespace GTS\Integration\Traveline\Infrastructure\Providers;

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
        $this->app->singleton(\GTS\Integration\Traveline\Infrastructure\Facade\ReservationFacadeInterface::class, \GTS\Integration\Traveline\Infrastructure\Facade\ReservationFacade::class);
        $this->app->singleton(\GTS\Integration\Traveline\Infrastructure\Facade\HotelFacadeInterface::class, \GTS\Integration\Traveline\Infrastructure\Facade\HotelFacade::class);

        $this->app->singleton(\GTS\Integration\Traveline\Domain\Adapter\ReservationAdapterInterface::class, \GTS\Integration\Traveline\Infrastructure\Adapter\ReservationAdapter::class);
        $this->app->singleton(\GTS\Integration\Traveline\Domain\Adapter\HotelAdapterInterface::class, \GTS\Integration\Traveline\Infrastructure\Adapter\HotelAdapter::class);
        $this->app->singleton(\GTS\Integration\Traveline\Domain\Adapter\TravelineAdapterInterface::class, function ($app) {
            $notificationsUrl = module('Traveline')->config('notifications_url');
            return new \GTS\Integration\Traveline\Infrastructure\Adapter\TravelineAdapter(app(ClientInterface::class), $notificationsUrl);
        });
    }
}
