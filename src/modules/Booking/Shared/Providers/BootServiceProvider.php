<?php

namespace Module\Booking\Shared\Providers;

use Sdk\Module\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(OrderServiceProvider::class);
        $this->app->register(BookingServiceProvider::class);
        $this->app->register(HotelBookingServiceProvider::class);
        $this->app->register(DetailsServiceProvider::class);
        $this->app->register(AdapterServiceProvider::class);
        $this->app->register(DomainEventServiceProvider::class);
    }

    public function boot(): void {}
}
