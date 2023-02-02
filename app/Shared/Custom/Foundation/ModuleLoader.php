<?php

namespace GTS\Shared\Custom\Foundation;

class ModuleLoader
{
    public function __construct(private readonly Application $app) {}

    public function loadByAbstract($abstract): bool
    {
        $module = $this->findInterface($abstract, 'Facade')
            ?? $this->findInterface($abstract, 'Port');
        if (!$module)
            return false;

        $this->app->modules()->loadModule($module);

        return true;
    }

    private function findInterface($abstract, $prefix)
    {
        if (!str_ends_with($abstract, $prefix . 'Interface'))
            return null;
        //!str_starts_with($abstract, 'GTS\\')
        //|| str_starts_with($abstract, 'GTS\\Shared')

        foreach ($this->app->modules()->registeredModules() as $module) {
            if (str_starts_with($abstract, $module->namespace('\\Infrastructure\\' . $prefix . '\\'))) {
                return $module;
            }
        }

        return null;
    }
}
