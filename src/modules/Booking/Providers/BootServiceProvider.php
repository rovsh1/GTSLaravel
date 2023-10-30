<?php

namespace Module\Booking\Providers;

use Module\Booking\Providers\ServiceBooking\BootServiceProvider as ServiceBootProvider;
use Sdk\Module\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(OrderServiceProvider::class);
        $this->app->register(HotelBookingServiceProvider::class);
        $this->app->register(ServiceBootProvider::class);
        $this->app->register(RequestServiceProvider::class);

        $this->app->register(DomainEventServiceProvider::class);

        $this->app->register(SharedServiceProvider::class);
    }
}
