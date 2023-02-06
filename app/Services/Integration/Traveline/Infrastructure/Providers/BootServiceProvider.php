<?php

namespace GTS\Services\Integration\Traveline\Infrastructure\Providers;

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
        $this->app->singleton(\GTS\Services\Integration\Traveline\Infrastructure\Facade\ReservationFacadeInterface::class, \GTS\Services\Integration\Traveline\Infrastructure\Facade\ReservationFacade::class);
        $this->app->singleton(\GTS\Services\Integration\Traveline\Infrastructure\Facade\HotelFacadeInterface::class, \GTS\Services\Integration\Traveline\Infrastructure\Facade\HotelFacade::class);

        $this->app->singleton(\GTS\Services\Integration\Traveline\Domain\Adapter\ReservationAdapterInterface::class, \GTS\Services\Integration\Traveline\Infrastructure\Adapter\ReservationAdapter::class);
        $this->app->singleton(\GTS\Services\Integration\Traveline\Domain\Adapter\HotelAdapterInterface::class, \GTS\Services\Integration\Traveline\Infrastructure\Adapter\HotelAdapter::class);
        $this->app->singleton(\GTS\Services\Integration\Traveline\Domain\Adapter\TravelineAdapterInterface::class, function ($app) {
            $notificationsUrl = module('Traveline')->config('notifications_url');
            return new \GTS\Services\Integration\Traveline\Infrastructure\Adapter\TravelineAdapter(app(ClientInterface::class), $notificationsUrl);
        });
    }
}
