<?php

namespace GTS\Shared\Infrastructure\Support;

class ModulesCollection
{
    public function __construct(private readonly array $modules)
    {
    }

    public function names(): array
    {
        return $this->modules;
    }

    public function paths(): array
    {
        return array_map(fn($n) => app_path($n), $this->modules);
    }
}
