<?php

namespace Custom\Framework\Foundation;

use GTS\Application;

class ModuleLoader
{
    public function __construct(private readonly Application $app) {}

    public function loadByAbstract($abstract): bool
    {
        $module = $this->findInterface($abstract, 'Facade');
        if (!$module) {
            return false;
        }

        $this->app->modules()->loadModule($module);

        $this->app->bind($abstract, fn() => $module->get($abstract));

        return true;
    }

    private function findInterface($abstract, $prefix)
    {
        if (!str_ends_with($abstract, $prefix . 'Interface')) {
            return null;
        }

        foreach ($this->app->modules()->registeredModules() as $module) {
            if (str_starts_with($abstract, $module->namespace('\\Infrastructure\\' . $prefix . '\\'))) {
                return $module;
            }
        }

        return null;
    }
}
