<?php

namespace Module\Integration\Traveline\UI\Api\Providers;

use Module\Shared\UI\Common\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
