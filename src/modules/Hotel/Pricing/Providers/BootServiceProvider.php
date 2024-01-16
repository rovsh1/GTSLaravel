<?php

namespace Module\Hotel\Pricing\Providers;

use Illuminate\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(HotelServiceProvider::class);
        $this->app->register(MarkupServiceProvider::class);
    }
}
