<?php

namespace GTS\Shared\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

use GTS\Shared\Infrastructure\Support\ModulesCollection;

//use Ustabor\Shared\Domain\Event\DomainEventDispatcherInterface;
//use Ustabor\Shared\Application\Command\CommandBusInterface;
//use Ustabor\Shared\Application\Query\QueryBusInterface;
//use Ustabor\Shared\Application\Validation\ValidatorPipelineBehaviorInterface;
//use Ustabor\Shared\Infrastructure\Bus\CommandBus;
//use Ustabor\Shared\Infrastructure\Bus\DomainEventDispatcher;
//use Ustabor\Shared\Infrastructure\Bus\QueryBus;
//use Ustabor\Shared\Infrastructure\Bus\ValidatorPipelineBehavior;
//use Ustabor\Shared\Infrastructure\Support\ModulesCollection;

class AppServiceProvider extends ServiceProvider
{
    private array $modules = [
        'Hotel',
        'Services\\Traveline'
    ];

    public function register()
    {
//		$this->registerServices();
        $this->registerModules();
    }

//	private function registerServices() {
//		$this->app->singleton(ValidatorPipelineBehaviorInterface::class, function ($app) {
//			return new ValidatorPipelineBehavior($app);
//		});
//
//		$this->app->singleton(CommandBusInterface::class, function ($app) {
//			return new CommandBus($app, $app->get(ValidatorPipelineBehaviorInterface::class));
//		});
//
//		$this->app->singleton(QueryBusInterface::class, function ($app) {
//			return new QueryBus($app);
//		});
//
//		$this->app->singleton(DomainEventDispatcherInterface::class, function ($app) {
//			return new DomainEventDispatcher($app);
//		});
//	}

    private function registerModules()
    {
        $this->app->instance('modules', new ModulesCollection($this->modules));

        foreach ($this->modules as $module) {
            $this->app->register('\GTS\\' . $module . '\Infrastructure\Providers\BootServiceProvider');
        }
    }
}
