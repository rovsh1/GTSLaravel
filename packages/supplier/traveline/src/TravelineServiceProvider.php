<?php

namespace Pkg\Supplier\Traveline;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider;
use Pkg\Supplier\Traveline\Adapters\HotelAdapter;
use Pkg\Supplier\Traveline\Adapters\TravelineAdapter;
use Pkg\Supplier\Traveline\Factory\BookingDtoFactory;
use Pkg\Supplier\Traveline\Repository\HotelRepository;
use Pkg\Supplier\Traveline\Repository\RoomQuotaRepository;
use Pkg\Supplier\Traveline\Service\QuotaAndPriceUpdater;

class TravelineServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (!app()->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__ . '/../config/suppliers.php', 'suppliers');
        }
        //@todo пока перенес в сюда apps/api/app/Providers/BootServiceProvider.php
//        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootMigrations();

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

    protected function bootMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
