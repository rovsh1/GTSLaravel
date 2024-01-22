<?php

namespace Shared\Support\Module;

use Exception;
use Sdk\Module\Contracts\ModuleInterface;
use SplStack;

class ModuleRepository implements \Iterator
{
    use ModulesCollectionTrait;

    public readonly SplStack $callStack;

    public function __construct()
    {
        $this->callStack = new SplStack();
    }

    public function register(Module $module): void
    {
        $this->modules[] = $module;
    }

    public function moduleLoaded(string $name): bool
    {
        return $this->get($name)->isBooted();
    }

    public function loadedModules(): array
    {
        return array_filter($this->all(), fn($m) => $m->isBooted());
    }

    public function loadModule(ModuleInterface|string $module): void
    {
        if (is_string($module)) {
            if (!$this->has($module)) {
                throw new Exception('Module [' . $module . '] not found');
            }

            $module = $this->get($module);
        }

        $module->boot();
    }

    public function findByNamespace(string $abstract): ?Module
    {
        foreach ($this->modules as $module) {
            if ($module->hasSubclass($abstract)) {
                return $module;
            }
        }

        return null;
    }
}
