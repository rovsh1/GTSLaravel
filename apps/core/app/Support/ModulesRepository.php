<?php

namespace App\Core\Support;

use Custom\Framework\Foundation\Module;

class ModulesRepository
{
    private array $registeredModules = [];

    public function __construct(private $app) {}

    public function registeredModules(): array
    {
        return $this->registeredModules;
    }

    public function loadedModules(): array
    {
        return array_filter($this->registeredModules, fn($m) => $m->isBooted());
    }

    public function get(string $name): ?Module
    {
        foreach ($this->registeredModules as $module) {
            if ($module->is($name)) {
                return $module;
            }
        }

        return null;
    }

    public function has(string $name): bool
    {
        foreach ($this->registeredModules as $module) {
            if ($module->is($name)) {
                return true;
            }
        }

        return false;
    }

    public function names(): array
    {
        return array_map(fn(Module $module) => $module->name(), $this->registeredModules);
    }

    public function paths(): array
    {
        return array_map(fn(Module $module) => $module->path(), $this->registeredModules);
    }

    public function load(array $modulesConfig): void
    {
        foreach ($modulesConfig as $name => $config) {
            if (isset($config['enabled']) && $config['enabled'] === false) {
                continue;
            }

            $this->registerModule($name, $config);
        }

//        foreach ($this->registeredModules as $module) {
//            if ($module->isDeferred())
//                continue;
//
//            $this->loadModule($module);
//        }
    }

    public function loadModule(Module|string $module): void
    {
        if (is_string($module)) {
            if (!$this->has($module)) {
                throw new \Exception('Module [' . $module . '] not found');
            }

            $module = $this->get($module);
        }

        $module->boot();
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

        $this->registeredModules[$name] = new Module($name, $config);
    }
}
