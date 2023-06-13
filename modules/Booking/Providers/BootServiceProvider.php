<?php

namespace Module\Booking\Providers;

use Module\Booking\Airport\Providers\BootServiceProvider as AirportBootProvider;
use Module\Booking\Hotel\Domain;
use Module\Booking\Hotel\Infrastructure;
use Module\Booking\Hotel\Providers\BootServiceProvider as HotelBootProvider;
use Module\Booking\Order\Providers\BootServiceProvider as OrderBootProvider;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(OrderBootProvider::class);
        $this->app->register(HotelBootProvider::class);
        $this->app->register(AirportBootProvider::class);
    }
}
