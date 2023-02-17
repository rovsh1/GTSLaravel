<?php

namespace GTS\Integration\Traveline\Infrastructure\Providers;

use GTS\Integration\Traveline\Domain;
use GTS\Integration\Traveline\Infrastructure;
use GuzzleHttp\Client;
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
        $this->app->bind(ClientInterface::class, Client::class);

        $this->app->singleton(Infrastructure\Facade\ReservationFacadeInterface::class, Infrastructure\Facade\ReservationFacade::class);
        $this->app->singleton(Infrastructure\Facade\HotelFacadeInterface::class, Infrastructure\Facade\HotelFacade::class);

        $this->app->singleton(Domain\Adapter\ReservationAdapterInterface::class, Infrastructure\Adapter\ReservationAdapter::class);
        $this->app->singleton(Domain\Adapter\HotelAdapterInterface::class, Infrastructure\Adapter\HotelAdapter::class);
        $this->app->singleton(Domain\Adapter\TravelineAdapterInterface::class, function ($app) {
            $notificationsUrl = $app->config('notifications_url');
            return new Infrastructure\Adapter\TravelineAdapter(app(ClientInterface::class), $notificationsUrl);
        });
        $this->app->singleton(Domain\Repository\HotelRepositoryInterface::class, Infrastructure\Repository\HotelRepository::class);

        $this->app->singleton(Domain\Api\Service\QuotaAndPriceUpdater::class, function ($app) {
            return new Domain\Api\Service\QuotaAndPriceUpdater(
                app(Infrastructure\Adapter\HotelAdapter::class),
                app(Infrastructure\Repository\HotelRepository::class),
                $app->config('is_prices_for_residents')
            );
        });

    }
}
