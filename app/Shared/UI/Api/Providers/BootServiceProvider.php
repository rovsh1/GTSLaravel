<?php

namespace GTS\Shared\UI\Api\Providers;

use GTS\Shared\UI\Common\Support\BootServiceProvider as ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    protected $requiredModules = [
        'Traveline'
    ];

    protected $providers = [];

    public function register()
    {
        parent::register();

        app('modules')->registerModulesUI('Api');
    }
}
