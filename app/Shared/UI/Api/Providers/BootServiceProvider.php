<?php

namespace GTS\Shared\UI\Api\Providers;

use Illuminate\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{

    private $providers = [
        RouteServiceProvider::class,
    ];

    public function register()
    {
        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }
    }

}
