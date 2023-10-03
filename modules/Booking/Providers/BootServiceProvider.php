<?php

namespace Module\Booking\Providers;

use Module\Booking\Transfer\Providers\BootServiceProvider as TransferBootProvider;
use Module\Booking\Airport\Providers\BootServiceProvider as AirportBootProvider;
use Module\Booking\Common\Providers\BootServiceProvider as CommonBootProvider;
use Module\Booking\HotelBooking\Providers\BootServiceProvider as HotelBootProvider;
use Module\Booking\Order\Providers\BootServiceProvider as OrderBootProvider;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(CommonBootProvider::class);
        $this->app->register(OrderBootProvider::class);
        $this->app->register(HotelBootProvider::class);
        $this->app->register(AirportBootProvider::class);
        $this->app->register(TransferBootProvider::class);
    }
}
