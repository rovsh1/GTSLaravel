<?php

namespace Sdk\Module\Foundation\Providers;

use Sdk\Module\Bus\CommandBus;
use Sdk\Module\Bus\JobDispatcher;
use Sdk\Module\Bus\Middleware\ValidationMiddleware;
use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\Contracts\Bus\JobDispatcherInterface;
use Sdk\Module\Support\ServiceProvider;

class BusServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        $this->app->singleton(CommandBusInterface::class, function ($app) {
            //$app->get(ValidatorPipelineBehaviorInterface::class)
            return new CommandBus($app, [
                ValidationMiddleware::class
            ]);
        });
        $this->app->singleton(JobDispatcherInterface::class, function ($app) {
            return new JobDispatcher($app, []);
        });
    }
}
