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
        $this->app->singleton(Facade\ReservationFacadeInterface::class, Facade\ReservationFacade::class);
        $this->app->singleton(Facade\HotelFacadeInterface::class, Facade\HotelFacade::class);

        $this->app->singleton(Domain\Adapter\ReservationAdapterInterface::class, Infrastructure\Adapter\ReservationAdapter::class);
        $this->app->singleton(Domain\Adapter\HotelAdapterInterface::class, Infrastructure\Adapter\HotelAdapter::class);
        $this->app->singleton(Domain\Adapter\TravelineAdapterInterface::class, function ($app) {
            $notificationsUrl = module('Traveline')->config('notifications_url');
            return new Infrastructure\Adapter\TravelineAdapter(app(ClientInterface::class), $notificationsUrl);
        });
    }
}
