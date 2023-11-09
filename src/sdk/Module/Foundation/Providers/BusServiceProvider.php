<?php

namespace Sdk\Module\Foundation\Providers;

use Sdk\Module\Bus\JobDispatcher;
use Sdk\Module\Contracts\Bus\JobDispatcherInterface;
use Sdk\Module\Support\ServiceProvider;

class BusServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        $this->app->singleton(JobDispatcherInterface::class, function ($app) {
            return new JobDispatcher($app, []);
        });
    }
}
