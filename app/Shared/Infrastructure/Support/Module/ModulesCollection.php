<?php

namespace GTS\Shared\Infrastructure\Support\Module;

class ModulesCollection
{
    public function __construct(
        private readonly array $modules
    ) {}

    public function get(string $name): ?Module
    {
        foreach ($this->modules as $module) {
            if ($module->name() === $name) {
                return $module;
            }
        }
        return null;
    }

    public function names(): array
    {
        return array_map(fn(Module $module) => $module->name(), $this->modules);
    }

    public function paths(): array
    {
        return array_map(fn(Module $module) => $module->path(), $this->modules);
    }
}
