<?php

namespace GTS\Shared\UI\Api\Providers;

use Illuminate\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{

    private $providers = [
        //RouteServiceProvider::class,
    ];

    public function register()
    {
        $this->app->register(\GTS\Services\Traveline\UI\Api\Providers\BootServiceProvider::class);
    }

}
