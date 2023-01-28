<?php

namespace GTS\Shared\UI\Admin\Providers;

use Illuminate\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{

    private $providers = [
        //FormatServiceProvider::class,
        RouteServiceProvider::class,
        //AuthServiceProvider::class
    ];

    public function register()
    {
        //$this->app->instance('appSource', AppSource::API);

        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }

        foreach (app('modules') as $module) {
            $provider = $module->namespace('UI\Admin\Providers\BootServiceProvider');
            if (class_exists($provider))
                $this->app->register($provider);
        }
    }

}
