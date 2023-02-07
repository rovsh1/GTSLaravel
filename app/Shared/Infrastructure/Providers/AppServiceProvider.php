<?php

namespace GTS\Shared\Infrastructure\Providers;

use GTS\Shared\Infrastructure\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->registerModules();
    }
}
