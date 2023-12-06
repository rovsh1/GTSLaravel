<?php

namespace Module\Booking\Invoicing\Providers;

use Illuminate\Support\Facades\View;
use Module\Booking\Invoicing\Domain;
use Module\Booking\Shared\Providers\BootServiceProvider as SharedBookingServiceProvider;
use Sdk\Module\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(SharedBookingServiceProvider::class);
    }

    public function boot()
    {
        View::addLocation(base_path('resources/pdf-templates'));
    }

}
