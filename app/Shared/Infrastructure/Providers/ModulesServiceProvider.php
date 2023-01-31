<?php

namespace GTS\Shared\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

use GTS\Shared\Infrastructure\Support\Module\Module;
use GTS\Shared\Infrastructure\Support\Module\ModulesCollection;

class ModulesServiceProvider extends ServiceProvider
{
    public function register()
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
