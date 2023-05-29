<?php

namespace App\Core\Support;

use Sdk\Module\Foundation\Module;

interface ModulesRepositoryInterface
{
    public function registeredModules(): array;

    public function loadedModules(): array;

    public function get(string $name): ?Module;

    public function has(string $name): bool;

    public function names(): array;

    public function paths(): array;

    public function registerModule(Module $module): void;

    public function loadModule(Module|string $module): void;
}
