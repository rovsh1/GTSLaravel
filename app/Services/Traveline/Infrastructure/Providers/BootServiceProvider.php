<?php

namespace GTS\Services\Traveline\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

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
//        $this->app->singleton(Api\Registration\ApiInterface::class, Api\Registration\Api::class);
    }
}
