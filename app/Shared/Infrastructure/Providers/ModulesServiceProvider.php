<?php

namespace GTS\Shared\Infrastructure\Providers;

use GTS\Shared\Infrastructure\Support\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('modules', fn($app) => $app->modules());

        $this->app->modules()->load(config('modules'));
    }
}
