<?php

namespace Module\Booking\Notification\Providers;

use Illuminate\Support\Facades\View;
use Module\Booking\Moderation\Providers\DomainEventServiceProvider;
use Module\Booking\Shared\Providers\BootServiceProvider as SharedBookingServiceProvider;
use Sdk\Module\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(SharedBookingServiceProvider::class);
    }

    public function boot(): void
    {
        View::addLocation(base_path('resources/mail'));
        View::addLocation(base_path('resources/pdf-templates'));
    }
}
