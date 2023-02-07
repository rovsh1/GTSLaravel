<?php

namespace GTS\Shared\Custom\Foundation\Providers;

use GTS\Services\PortGateway\Infrastructure\Service\PortGateway;
use GTS\Shared\Application\Command\CommandBusInterface;
use GTS\Shared\Application\Query\QueryBusInterface;
use GTS\Shared\Application\Validation\ValidatorPipelineBehaviorInterface;
use GTS\Shared\Domain\Port\PortGatewayInterface;
use GTS\Shared\Infrastructure\Bus;
use GTS\Shared\Infrastructure\Support\ServiceProvider;

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

        $this->app->singleton(PortGatewayInterface::class, fn($app) => new PortGateway($app));
    }
}
