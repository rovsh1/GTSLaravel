<?php

namespace App\Core\Providers;

use Custom\Framework\Contracts\Event\IntegrationEventHandlerInterface;
use Custom\Framework\Foundation\Module;
use Custom\Framework\Foundation\Support\Providers\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    public function register()
    {
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
        $module->instance(IntegrationEventHandlerInterface::class, $this->app->get(IntegrationEventHandlerInterface::class));

        $this->app->registerModule($module);
    }
}
