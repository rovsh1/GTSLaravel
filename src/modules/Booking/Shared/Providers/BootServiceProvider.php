<?php

namespace Module\Booking\Shared\Providers;

use Module\Booking\Shared\Providers\ServiceBooking\BootServiceProvider as ServiceBootProvider;
use Sdk\Module\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(OrderServiceProvider::class);
        $this->app->register(HotelBookingServiceProvider::class);
        $this->app->register(ServiceBootProvider::class);

        $this->app->register(DomainEventServiceProvider::class);

        $this->app->register(SharedServiceProvider::class);
    }
}
