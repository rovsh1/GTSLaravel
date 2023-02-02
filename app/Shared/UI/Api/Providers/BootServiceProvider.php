<?php

namespace GTS\Shared\UI\Api\Providers;

use GTS\Shared\UI\Common\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        app('modules')->registerModulesUI('Api');
    }
}
