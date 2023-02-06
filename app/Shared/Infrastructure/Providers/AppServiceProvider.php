<?php

namespace GTS\Shared\Infrastructure\Providers;

use GTS\Shared\Application\Command\CommandBusInterface;
use GTS\Shared\Application\Query\QueryBusInterface;
use GTS\Shared\Application\Validation\ValidatorPipelineBehaviorInterface;
use GTS\Shared\Infrastructure\Bus;
use GTS\Shared\Infrastructure\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerServices();

        $this->app->register(ModulesServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
    }

    private function registerServices()
    {
        $this->app->singleton(ValidatorPipelineBehaviorInterface::class, fn($app) => new Bus\ValidatorPipelineBehavior($app));

        $this->app->singleton(CommandBusInterface::class, function ($app) {
            //$app->get(ValidatorPipelineBehaviorInterface::class)
            return new Bus\CommandBus($app, [
                Bus\Middleware\ValidationMiddleware::class
            ]);
        });

        $this->app->singleton(QueryBusInterface::class, fn($app) => new Bus\QueryBus($app));

        $this->app->singleton(Bus\Port\GatewayInterface::class, fn($app) => new Bus\Port\Gateway($app));

//		$this->app->singleton(DomainEventDispatcherInterface::class, function ($app) {
//			return new DomainEventDispatcher($app);
//		});
    }
}
