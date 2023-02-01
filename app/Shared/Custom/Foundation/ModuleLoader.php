<?php

namespace GTS\Shared\Custom\Foundation;

class ModuleLoader
{
    public function __construct(private readonly Application $app) {}

    public function load($abstract): bool
    {
        if (!str_ends_with($abstract, 'FacadeInterface'))
            return false;
        //!str_starts_with($abstract, 'GTS\\')
        //|| str_starts_with($abstract, 'GTS\\Shared')

        foreach ($this->app->modules()->registeredModules() as $module) {
            if (str_starts_with($abstract, $module->namespace('\\Infrastructure\\Facade\\'))) {
                $this->app->loadModule($module->name());
                //var_dump($module->name());
                return true;
            }
        }

        return false;
    }
}
