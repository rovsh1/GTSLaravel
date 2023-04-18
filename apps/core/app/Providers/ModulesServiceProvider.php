<?php

namespace App\Core\Providers;

use Custom\Framework\Contracts\Event\IntegrationEventHandlerInterface;
use Custom\Framework\Contracts\Notification\NotificationGatewayInterface;
use Custom\Framework\Contracts\PortGateway\PortGatewayInterface;
use Custom\Framework\Foundation\Module;
use Custom\Framework\Foundation\Support\Providers\ServiceProvider;
use Module\Shared\Providers\BootServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    private array $globalBindings = [
        PortGatewayInterface::class,
        IntegrationEventHandlerInterface::class,
        NotificationGatewayInterface::class,
    ];

    public function register()
    {
        $this->app->register(BootServiceProvider::class);

        $this->load(config('modules'));
    }

    private function load(array $modulesConfig): void
    {
        foreach ($modulesConfig as $name => $config) {
            if (isset($config['enabled']) && $config['enabled'] === false) {
                continue;
            }
            $this->registerModule($name, $config);
        }
    }

    private function registerModule($name, $config): void
    {
        if (!isset($config['path'])) {
            $config['path'] = app_path($name);
        }

        if (!isset($config['namespace'])) {
            $ns = str_replace(modules_path(), '', $config['path']);
            $ns = str_replace('/', '\\', $ns);
            $config['namespace'] = 'Module' . $ns;
        }

        $module = new Module($name, $config);
        foreach ($this->globalBindings as $abstract) {
            $module->singleton($abstract, function () use ($abstract) {
                return $this->app->get($abstract);
            });
        }

        $this->app->registerModule($module);
    }
}
