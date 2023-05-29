<?php

namespace Sdk\Module\Foundation\Providers;

use Sdk\Module\Bus\CommandBus;
use Sdk\Module\Bus\JobDispatcher;
use Sdk\Module\Bus\Middleware\ValidationMiddleware;
use Sdk\Module\Bus\QueryBus;
use Sdk\Module\Contracts\Bus\CommandBusInterface;
use Sdk\Module\Contracts\Bus\JobDispatcherInterface;
use Sdk\Module\Contracts\Bus\QueryBusInterface;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BusServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
//        $this->app->singleton(ValidatorPipelineBehaviorInterface::class, fn($app) => new Bus\ValidatorPipelineBehavior($app));

        $this->app->singleton(CommandBusInterface::class, function ($app) {
            //$app->get(ValidatorPipelineBehaviorInterface::class)
            return new CommandBus($app, [
                ValidationMiddleware::class
            ]);
        });

        $this->app->singleton(QueryBusInterface::class, fn($app) => new QueryBus($app));

        $this->app->singleton(JobDispatcherInterface::class, function ($app) {
            return new JobDispatcher($app, []);
        });
    }
}
