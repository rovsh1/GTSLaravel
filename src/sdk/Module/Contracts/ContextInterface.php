<?php

namespace Sdk\Module\Contracts;

interface ContextInterface
{
    public function module(): ModuleInterface;

    public function toArray(): array;
}
