<?php

namespace Sdk\Shared\Support\ApplicationContext\Concerns;

trait ModuleContextTrait
{
    public function setModule(string $module): void
    {
        $this->set('module', $module);
    }

    public function module(): string
    {
        return $this->get('module');
    }
}