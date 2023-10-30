<?php

namespace App\Shared\Support\Module;

use App\Shared\Contracts\Module\ModuleAdapterInterface;

trait ModulesCollectionTrait
{
    /**
     * @var ModuleAdapterInterface[]
     */
    protected array $modules = [];

    private int $position = 0;

    public function has(string $name): bool
    {
        /** @var ModuleAdapterInterface $module */
        foreach ($this->modules as $module) {
            if ($module->is($name)) {
                return true;
            }
        }

        return false;
    }

    public function get(string $name): ?ModuleAdapterInterface
    {
        /** @var ModuleAdapterInterface $module */
        foreach ($this->modules as $module) {
            if ($module->is($name)) {
                return $module;
            }
        }

        return null;
    }

    public function all(): array
    {
        return $this->modules;
    }

    public function current(): ModuleAdapterInterface
    {
        return $this->modules[$this->position];
    }

    public function next(): void
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

    public function rewind(): void
    {
        $this->position = 0;
    }
}
