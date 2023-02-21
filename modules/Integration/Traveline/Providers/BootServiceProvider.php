<?php

namespace Module\Integration\Traveline\Providers;

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

        $this->app->singleton(\Module\Integration\Traveline\Infrastructure\Facade\ReservationFacadeInterface::class, \Module\Integration\Traveline\Infrastructure\Facade\ReservationFacade::class);
        $this->app->singleton(\Module\Integration\Traveline\Infrastructure\Facade\HotelFacadeInterface::class, \Module\Integration\Traveline\Infrastructure\Facade\HotelFacade::class);

        $this->app->singleton(\Module\Integration\Traveline\Domain\Adapter\ReservationAdapterInterface::class, \Module\Integration\Traveline\Infrastructure\Adapter\ReservationAdapter::class);
        $this->app->singleton(\Module\Integration\Traveline\Domain\Adapter\HotelAdapterInterface::class, \Module\Integration\Traveline\Infrastructure\Adapter\HotelAdapter::class);
        $this->app->singleton(\Module\Integration\Traveline\Domain\Adapter\TravelineAdapterInterface::class, function ($app) {
            $notificationsUrl = $app->config('notifications_url');
            return new \Module\Integration\Traveline\Infrastructure\Adapter\TravelineAdapter(app(ClientInterface::class), $notificationsUrl);
        });
        $this->app->singleton(\Module\Integration\Traveline\Domain\Repository\HotelRepositoryInterface::class, \Module\Integration\Traveline\Infrastructure\Repository\HotelRepository::class);

        $this->app->singleton(\Module\Integration\Traveline\Domain\Api\Service\QuotaAndPriceUpdater::class, function ($app) {
            return new \Module\Integration\Traveline\Domain\Api\Service\QuotaAndPriceUpdater(
                app(\Module\Integration\Traveline\Infrastructure\Adapter\HotelAdapter::class),
                app(\Module\Integration\Traveline\Infrastructure\Repository\HotelRepository::class),
                $app->config('is_prices_for_residents')
            );
        });

    }
}
