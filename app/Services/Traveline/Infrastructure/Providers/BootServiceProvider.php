<?php

namespace GTS\Services\Traveline\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

use GTS\Services\Traveline\Infrastructure\Adapter;
use GTS\Services\Traveline\Infrastructure\Api;

class BootServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register()
    {
        $this->registerInterfaces();
    }

    private function registerInterfaces()
    {
        $this->app->singleton(Api\Hotel\Search\ApiInterface::class, Api\Hotel\Search\Api::class);
        $this->app->singleton(Adapter\Hotel\AdapterInterface::class, Adapter\Hotel\Adapter::class);
    }
}
