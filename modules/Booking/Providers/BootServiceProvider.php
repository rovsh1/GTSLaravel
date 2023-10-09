<?php

namespace Module\Booking\Providers;

use Module\Booking\Providers\AirportBooking\BootServiceProvider as AirportBootProvider;
use Module\Booking\Providers\HotelBooking\BootServiceProvider as HotelBootProvider;
use Module\Booking\Providers\Order\BootServiceProvider as OrderBootProvider;
use Module\Booking\Providers\Shared\BootServiceProvider as CommonBootProvider;
use Module\Booking\Providers\TransferBooking\BootServiceProvider as TransferBootProvider;
use Module\Booking\Providers\ServiceBooking\BootServiceProvider as ServiceBootProvider;
use Module\Booking\Providers\BookingRequest\BootServiceProvider as BookingRequestBootProvider;
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
        $this->app->register(ServiceBootProvider::class);
        $this->app->register(BookingRequestBootProvider::class);
    }
}
