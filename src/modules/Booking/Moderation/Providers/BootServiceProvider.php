<?php

namespace Module\Booking\Moderation\Providers;

use Module\Booking\Shared\Providers\BootServiceProvider as SharedBookingServiceProvider;
use Sdk\Module\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(SharedBookingServiceProvider::class);
    }

    public function boot(): void
    {
    }
}
