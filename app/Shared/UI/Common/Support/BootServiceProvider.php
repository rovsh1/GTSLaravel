<?php

namespace GTS\Shared\UI\Common\Support;

class BootServiceProvider extends ServiceProvider
{
    protected function registerModulesUI(string $port): void
    {
        $bootProviderNamespace = 'UI\\' . $port . '\Providers\BootServiceProvider';

        foreach (app('modules')->registeredModules() as $module) {
            $provider = $module->namespace($bootProviderNamespace);
            if (class_exists($provider)) {
                $this->app->register($provider);
            }
        }
    }
}
