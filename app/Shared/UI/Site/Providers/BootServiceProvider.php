<?php

namespace GTS\Shared\UI\Site\Providers;

use GTS\Shared\UI\Common\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        //FormatServiceProvider::class,
        //AuthServiceProvider::class

        app('modules')->registerModulesUI('Site');
    }
}
