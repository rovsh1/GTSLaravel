<?php

namespace Module\Pricing\Providers;

use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(HotelServiceProvider::class);
        $this->app->register(MarkupServiceProvider::class);
    }
}
