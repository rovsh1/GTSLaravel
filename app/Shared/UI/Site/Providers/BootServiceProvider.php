<?php

namespace GTS\Shared\UI\Site\Providers;

use GTS\Shared\UI\Common\Support\BootServiceProvider as ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    protected $providers = [
        //FormatServiceProvider::class,
        RouteServiceProvider::class,
        //AuthServiceProvider::class
    ];

    public function register()
    {
        parent::register();

        app('modules')->registerModulesUI('Site');
    }
}
