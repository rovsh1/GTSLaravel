<?php

namespace Module\Pricing\Providers;

use Sdk\Module\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(HotelServiceProvider::class);
        $this->app->register(MarkupServiceProvider::class);
    }
}
