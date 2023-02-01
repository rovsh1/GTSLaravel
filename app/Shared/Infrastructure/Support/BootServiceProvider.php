<?php

namespace GTS\Shared\Infrastructure\Support;

use Illuminate\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    protected $providers = [];

    public function register()
    {
        foreach ($this->providers as $provider) {
            $this->app->register($provider);
        }
    }
}
