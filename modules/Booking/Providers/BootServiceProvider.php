<?php

namespace Module\Booking\Providers;

use Module\Booking\Airport\Providers\BootServiceProvider as AirportBootProvider;
use Module\Booking\Common\Providers\BootServiceProvider as CommonBootProvider;
use Module\Booking\HotelBooking\Providers\BootServiceProvider as HotelBootProvider;
use Module\Booking\Order\Providers\BootServiceProvider as OrderBootProvider;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        //@todo без этого не работает includes
//        \View::addLocation($this->app->config('templates_path'));

        $this->app->register(CommonBootProvider::class);
        $this->app->register(OrderBootProvider::class);
        $this->app->register(HotelBootProvider::class);
        $this->app->register(AirportBootProvider::class);
    }
}
