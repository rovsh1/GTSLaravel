<?php

namespace Module\Booking\Moderation\Providers;

use Module\Booking\Shared\Providers\BootServiceProvider as SharedBookingServiceProvider;
use Illuminate\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(SharedBookingServiceProvider::class);
        $this->app->register(DomainEventServiceProvider::class);
    }

    public function boot(): void
    {
    }
}
