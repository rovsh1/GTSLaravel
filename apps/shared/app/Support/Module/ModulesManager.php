<?php

namespace App\Shared\Support\Module;

use App\Shared\Contracts\Module\ModuleAdapterInterface;
use Exception;
use Sdk\Module\Contracts\ModuleInterface;

class ModulesManager implements \Iterator
{
    use ModulesCollectionTrait;

    public function __construct()
    {
    }

    public function register(ModuleAdapterInterface $module): void
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
}
