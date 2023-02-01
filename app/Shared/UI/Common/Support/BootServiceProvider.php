<?php

namespace GTS\Shared\UI\Common\Support;

use Illuminate\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    protected $requiredModules = [];

    protected $providers = [];

    public function register()
    {
        foreach ($this->requiredModules as $module) {
            $this->app->loadModule($module);
        }

        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }
    }
}
