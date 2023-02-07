<?php

namespace Custom\Framework\Foundation\Providers;

use Custom\Framework\Bus;
use Custom\Framework\Bus\CommandBusInterface;
use Custom\Framework\Bus\QueryBusInterface;
use Custom\Framework\Foundation\Support\ServiceProvider;
use Custom\Framework\Validation\ValidatorPipelineBehaviorInterface;

class BusServiceProvider extends ServiceProvider
{
    public function register() {}

    public function boot()
    {
        $this->app->singleton(ValidatorPipelineBehaviorInterface::class, fn($app) => new Bus\ValidatorPipelineBehavior($app));

        $this->app->singleton(CommandBusInterface::class, function ($app) {
            //$app->get(ValidatorPipelineBehaviorInterface::class)
            return new Bus\CommandBus($app, [
                Bus\Middleware\ValidationMiddleware::class
            ]);
        });

        $this->app->singleton(QueryBusInterface::class, fn($app) => new Bus\QueryBus($app));
    }
}
