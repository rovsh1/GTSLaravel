<?php

namespace GTS\Shared\UI\Admin\Providers;

use GTS\Shared\UI\Common\Support\BootServiceProvider as ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    protected $requiredModules = [
        'Administrator'
    ];

    protected $providers = [
        FormatServiceProvider::class,
        RouteServiceProvider::class,
        ViewServiceProvider::class
    ];

    public function register()
    {
        parent::register();

        app('modules')->registerModulesUI('Admin');
    }
}
