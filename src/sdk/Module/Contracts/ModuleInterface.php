<?php

namespace Sdk\Module\Contracts;

interface ModuleInterface
{
    public function name(): string;

    public function is(string|ModuleInterface $name): bool;
}
