<?php

namespace Sdk\Module\Foundation;

use Exception;
use Sdk\Module\Contracts\ModuleInterface;
use Sdk\Module\Foundation\Support\ModulesCollection;

class ModulesManager
{
    private string $modulesNamespace;

    private string $modulesPath;

    private ModulesCollection $modules;

    public function __construct(
        string $modulesNamespace,
        string $modulesPath
    ) {
        $this->modulesNamespace = $modulesNamespace;
        $this->modulesPath = $modulesPath;
        $this->modules = new ModulesCollection();
    }

    public function modules(): ModulesCollection
    {
        return $this->modules;
    }

    public function moduleLoaded(string $name): bool
    {
        return $this->modules->get($name)->isBooted();
    }

    public function loadedModules(): array
    {
        return array_filter($this->modules->all(), fn($m) => $m->isBooted());
    }

    /**
     * @param ModuleInterface|string $module
     * @return void
     * @throws Exception
     */
    public function loadModule($module): void
    {
        if (is_string($module)) {
            if (!$this->hasModule($module)) {
                throw new Exception('Module [' . $module . '] not found');
            }

            $module = $this->get($module);
        }

        $module->boot();
    }

    public function get(string $name): ?ModuleInterface
    {
        return $this->modules->get($name);
    }

    public function hasModule(string $name): bool
    {
        return $this->modules->has($name);
    }

    public function findByNamespace(string $abstract): ?ModuleInterface
    {
        foreach ($this->modules as $module) {
            if ($module->hasSubclass($abstract)) {
                return $module;
            }
        }

        return null;
    }

    public function modulesPath($path = ''): string
    {
        return $this->modulesPath . ($path !== '' ? DIRECTORY_SEPARATOR . $path : '');
    }

    public function modulesNamespace($ns = ''): string
    {
        return $this->modulesNamespace . ($ns !== '' ? '\\' . $ns : '');
    }
}
