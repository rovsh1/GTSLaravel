<?php

namespace GTS\Integration\Traveline\UI\Api\Providers;

use GTS\Shared\UI\Common\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
