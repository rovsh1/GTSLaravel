<?php

namespace Custom\Framework\Foundation\Providers;

use Custom\Framework\Bus\CommandBusInterface;
use Custom\Framework\Bus\QueryBusInterface;
use Custom\Framework\Validation\ValidatorPipelineBehaviorInterface;
use GTS\Shared\Infrastructure\Bus;
use GTS\Shared\Infrastructure\Support\ServiceProvider;

class BusServiceProvider extends ServiceProvider
{
    public function register() {}

    public function boot()
    {
        $this->app->singleton(ValidatorPipelineBehaviorInterface::class, fn($app) => new \Custom\Framework\Bus\ValidatorPipelineBehavior($app));

        $this->app->singleton(CommandBusInterface::class, function ($app) {
            //$app->get(ValidatorPipelineBehaviorInterface::class)
            return new \Custom\Framework\Bus\CommandBus($app, [
                \Custom\Framework\Bus\Middleware\ValidationMiddleware::class
            ]);
        });

        $this->app->singleton(QueryBusInterface::class, fn($app) => new \Custom\Framework\Bus\QueryBus($app));
    }
}
