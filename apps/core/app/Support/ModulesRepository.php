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

    public function registerModule(Module $module): void
    {
        $this->registeredModules[] = $module;
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
}
