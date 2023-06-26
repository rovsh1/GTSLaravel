<?php

namespace Module\Booking\PriceCalculator\Providers;

use Module\Booking\PriceCalculator\Domain;
use Module\Booking\PriceCalculator\Infrastructure;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public $singletons = [
        Domain\Adapter\HotelAdapterInterface::class => Infrastructure\Adapter\HotelAdapter::class,
        Domain\Adapter\ClientAdapterInterface::class => Infrastructure\Adapter\ClientAdapter::class,
    ];

    public function register()
    {
        $this->app->register(EventServiceProvider::class);
    }
}
