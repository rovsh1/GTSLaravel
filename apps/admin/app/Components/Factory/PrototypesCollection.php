<?php

namespace App\Admin\Components\Factory;

class PrototypesCollection implements \Iterator
{
    private array $prototypes = [];

    private int $position;

    public function __construct()
    {
        $this->position = 0;
    }

    public function all(): array
    {
        return $this->prototypes;
    }

    public function add(Prototype $prototype): void
    {
        $this->prototypes[] = $prototype;
    }

    public function has(string $key): bool
    {
        foreach ($this->prototypes as $prototype) {
            if ($prototype->is($key)) {
                return true;
            }
        }
        return false;
    }

    public function get(string $key): ?Prototype
    {
        foreach ($this->prototypes as $prototype) {
            if ($prototype->is($key)) {
                return $prototype;
            }
        }
        return null;
    }

    public function current(): Prototype
    {
        return $this->prototypes[$this->position];
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->prototypes[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }
}
