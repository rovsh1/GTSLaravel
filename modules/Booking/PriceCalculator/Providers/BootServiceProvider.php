<?php

namespace Module\Booking\PriceCalculator\Providers;

use Module\Booking\PriceCalculator\Domain;
use Module\Booking\PriceCalculator\Infrastructure;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(EventServiceProvider::class);

        $this->app->singleton(
            Domain\Adapter\HotelAdapterInterface::class,
            Infrastructure\Adapter\HotelAdapter::class
        );
    }
}
