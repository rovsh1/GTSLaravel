<?php

namespace Pkg\Supplier\Traveline\Providers;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider;
use Pkg\Supplier\Traveline\Adapters\HotelAdapter;
use Pkg\Supplier\Traveline\Adapters\TravelineAdapter;
use Pkg\Supplier\Traveline\Factory\BookingDtoFactory;
use Pkg\Supplier\Traveline\Repository\HotelRepository;
use Pkg\Supplier\Traveline\Repository\RoomQuotaRepository;
use Pkg\Supplier\Traveline\Service\QuotaAndPriceUpdater;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->bootMigrations();
        $this->mergeConfigFrom(__DIR__ . '/../../config/traveline.php', 'suppliers.traveline');
        $this->app->register(EventServiceProvider::class);
    }

    public function boot(): void
    {
        $this->app->bind(ClientInterface::class, Client::class);

        $this->app->singleton(TravelineAdapter::class, function ($app) {
            return new TravelineAdapter(
                $app->make(ClientInterface::class),
                config('suppliers.traveline.notifications_url')
            );
        });

        $this->app->singleton(QuotaAndPriceUpdater::class, function ($app) {
            return new QuotaAndPriceUpdater(
                $app->make(HotelAdapter::class),
                $app->make(HotelRepository::class),
                $app->make(RoomQuotaRepository::class),
                config('suppliers.traveline.supported_currencies'),
                config('suppliers.traveline.is_prices_for_residents'),
            );
        });

        $this->app->singleton(BookingDtoFactory::class, function () {
            return new BookingDtoFactory(
                config('suppliers.traveline.timezone'),
            );
        });
    }

    protected function bootMigrations(): void
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        }
    }
}
