<?php

namespace Sdk\Module\Support;

use Sdk\Module\Contracts\ContextInterface;
use Sdk\Module\Contracts\ModuleInterface;

class Context implements ContextInterface
{
    public function __construct(
        private readonly ModuleInterface $module
    ) {
    }

    public function module(): ModuleInterface
    {
        return $this->module;
    }

    public function toArray(): array
    {
        return [
            'module' => $this->module->name()
        ];
    }
}
