<?php

namespace Supplier\Traveline\Providers;

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

        $this->app->singleton(\Supplier\Traveline\Domain\Entity\ConfigInterface::class, function ($app) {
            return new \Supplier\Traveline\Domain\Entity\Config(
                supportedCurrencies: $app->config('supported_currencies')
            );
        });

        $this->app->singleton(
            \Supplier\Traveline\Domain\Adapter\ReservationAdapterInterface::class,
            \Supplier\Traveline\Infrastructure\Adapter\ReservationAdapter::class
        );
        $this->app->singleton(
            \Supplier\Traveline\Domain\Adapter\HotelAdapterInterface::class, \Supplier\Traveline\Infrastructure\Adapter\HotelAdapter::class);
        $this->app->singleton(\Supplier\Traveline\Domain\Adapter\TravelineAdapterInterface::class, function ($app) {
            $notificationsUrl = $app->config('notifications_url');
            return new \Supplier\Traveline\Infrastructure\Adapter\TravelineAdapter(app(ClientInterface::class), $notificationsUrl);
        });
        $this->app->singleton(
            \Supplier\Traveline\Domain\Repository\HotelRepositoryInterface::class,
            \Supplier\Traveline\Infrastructure\Repository\HotelRepository::class
        );

        $this->app->singleton(\Supplier\Traveline\Application\Service\QuotaAndPriceUpdater::class, function ($app) {
            return new \Supplier\Traveline\Application\Service\QuotaAndPriceUpdater(
                app(\Supplier\Traveline\Infrastructure\Adapter\HotelAdapter::class),
                app(\Supplier\Traveline\Infrastructure\Repository\HotelRepository::class),
                app(\Supplier\Traveline\Domain\Service\HotelRoomCodeGenerator::class),
                app(\Supplier\Traveline\Domain\Entity\ConfigInterface::class),
                $app->config('is_prices_for_residents')
            );
        });

        $this->app->singleton(
            \Supplier\Traveline\Domain\Service\HotelRoomCodeGeneratorInterface::class,
            \Supplier\Traveline\Domain\Service\HotelRoomCodeGenerator::class
        );
    }
}
