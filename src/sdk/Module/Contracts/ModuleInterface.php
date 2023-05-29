<?php

namespace Sdk\Module\Contracts;

use Sdk\Module\Foundation\Module;

interface ModuleInterface
{
    public function name(): string;

    public function is(string|Module $name): bool;

    public function hasSubclass(string $abstract): bool;
}
