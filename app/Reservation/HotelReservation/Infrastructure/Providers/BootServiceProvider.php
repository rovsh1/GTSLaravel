<?php

namespace GTS\Reservation\HotelReservation\Infrastructure\Providers;

use GTS\Shared\Infrastructure\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(EventServiceProvider::class);
    }
}
