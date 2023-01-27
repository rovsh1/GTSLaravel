<?php

namespace GTS\Shared\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

use GTS\Shared\Application\Command\CommandBusInterface;
use GTS\Shared\Application\Query\QueryBusInterface;
use GTS\Shared\Application\Validation\ValidatorPipelineBehaviorInterface;
use GTS\Shared\Infrastructure\Bus;
use GTS\Shared\Infrastructure\Support\Module\Module;
use GTS\Shared\Infrastructure\Support\Module\ModulesCollection;

class AppServiceProvider extends ServiceProvider
{

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
        $modules = [];
        foreach (config('modules') as $name => $config) {
            if (isset($config['enabled']) && $config['enabled'] === false) {
                continue;
            }

            $modules[] = $this->moduleFactory($name, $config);
        }

        $this->app->instance('modules', new ModulesCollection($modules));

        foreach ($modules as $module) {
            $this->app->register($module->namespace('Infrastructure\Providers\BootServiceProvider'));
        }
    }

    private function moduleFactory($name, $config): Module
    {
        if (!isset($config['path'])) {
            $config['path'] = app_path($name);
        }

        if (!isset($config['namespace'])) {
            $ns = str_replace(app_path(), '', $config['path']);
            $ns = str_replace('/', '\\', $ns);
            $config['namespace'] = 'GTS' . $ns;
        }

        return new Module($name, $config);
    }
}
