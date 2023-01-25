<?php

namespace GTS\Shared\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

use GTS\Shared\Application\Command\CommandBusInterface;
use GTS\Shared\Application\Query\QueryBusInterface;
use GTS\Shared\Application\Validation\ValidatorPipelineBehaviorInterface;
use GTS\Shared\Infrastructure\Support\ModulesCollection;
use GTS\Shared\Infrastructure\Bus;

class AppServiceProvider extends ServiceProvider
{
    private array $modules = [
        'Hotel',
        'Services\\Traveline'
    ];

    public function register()
    {
        $this->registerServices();
        $this->registerModules();
    }

    private function registerServices()
    {
        $this->app->singleton(ValidatorPipelineBehaviorInterface::class, function ($app) {
            return new Bus\ValidatorPipelineBehavior($app);
        });

        $this->app->singleton(CommandBusInterface::class, function ($app) {
            return new Bus\CommandBus($app, $app->get(ValidatorPipelineBehaviorInterface::class));
        });

        $this->app->singleton(QueryBusInterface::class, function ($app) {
            return new Bus\QueryBus($app);
        });

//		$this->app->singleton(DomainEventDispatcherInterface::class, function ($app) {
//			return new DomainEventDispatcher($app);
//		});
    }

    private function registerModules()
    {
        $this->app->instance('modules', new ModulesCollection($this->modules));

        foreach ($this->modules as $module) {
            $this->app->register('\GTS\\' . $module . '\Infrastructure\Providers\BootServiceProvider');
        }
    }
}
