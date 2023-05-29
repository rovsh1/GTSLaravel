<?php

namespace Sdk\Module\Foundation\Support;

use Sdk\Module\Contracts\ModuleInterface;

class ModulesCollection implements \Iterator
{
    private array $modules = [];

    private int $position = 0;

    public function has(string $name): bool
    {
        /** @var ModuleInterface $module */
        foreach ($this->modules as $module) {
            if ($module->is($name)) {
                return true;
            }
        }
        return false;
    }

    public function get(string $name): ?ModuleInterface
    {
        /** @var ModuleInterface $module */
        foreach ($this->modules as $module) {
            if ($module->is($name)) {
                return $module;
            }
        }
        return null;
    }

    public function add(ModuleInterface $module): void
    {
        $this->modules[] = $module;
    }

    public function all(): array
    {
        return $this->modules;
    }

    public function current(): ModuleInterface
    {
        return $this->modules[$this->position];
    }

    public function next()
    {
        $this->position++;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->modules[$this->position]);
    }

    public function rewind()
    {
        $this->position = 0;
    }
}
