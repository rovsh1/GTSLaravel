<?php

namespace Module\Integration\Traveline\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider;
use Module\Integration\Traveline\Application;
use Module\Integration\Traveline\Domain;
use Module\Integration\Traveline\Infrastructure;

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

        $this->app->singleton(Domain\Entity\ConfigInterface::class, function ($app) {
            return new Domain\Entity\Config(
                supportedCurrencies: $app->config('supported_currencies')
            );
        });

        $this->app->singleton(
            Domain\Adapter\ReservationAdapterInterface::class,
            Infrastructure\Adapter\ReservationAdapter::class
        );
        $this->app->singleton(Domain\Adapter\HotelAdapterInterface::class, Infrastructure\Adapter\HotelAdapter::class);
        $this->app->singleton(Domain\Adapter\TravelineAdapterInterface::class, function ($app) {
            $notificationsUrl = $app->config('notifications_url');
            return new Infrastructure\Adapter\TravelineAdapter(app(ClientInterface::class), $notificationsUrl);
        });
        $this->app->singleton(
            Domain\Repository\HotelRepositoryInterface::class,
            Infrastructure\Repository\HotelRepository::class
        );

        $this->app->singleton(Application\Service\QuotaAndPriceUpdater::class, function ($app) {
            return new Application\Service\QuotaAndPriceUpdater(
                app(Infrastructure\Adapter\HotelAdapter::class),
                app(Infrastructure\Repository\HotelRepository::class),
                app(Domain\Service\HotelRoomCodeGenerator::class),
                app(Domain\Entity\ConfigInterface::class),
                $app->config('is_prices_for_residents')
            );
        });

        $this->app->singleton(
            Domain\Service\HotelRoomCodeGeneratorInterface::class,
            Domain\Service\HotelRoomCodeGenerator::class
        );
    }
}
