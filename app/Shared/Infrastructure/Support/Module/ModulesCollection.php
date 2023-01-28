<?php

namespace GTS\Shared\Infrastructure\Support\Module;

class ModulesCollection implements \Iterator
{
    private int $cursor = 0;

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

    public function has(string $name): bool
    {
        foreach ($this->modules as $module) {
            if ($module->name() === $name) {
                return true;
            }
        }
        return false;
    }

    public function names(): array
    {
        return array_map(fn(Module $module) => $module->name(), $this->modules);
    }

    public function paths(): array
    {
        return array_map(fn(Module $module) => $module->path(), $this->modules);
    }

    public function current(): mixed
    {
        return $this->modules[$this->cursor];
    }

    public function next(): void
    {
        ++$this->cursor;
    }

    public function key(): mixed
    {
        return $this->cursor;
    }

    public function valid(): bool
    {
        return isset($this->modules[$this->cursor]);
    }

    public function rewind(): void
    {
        $this->cursor = 0;
    }
}
