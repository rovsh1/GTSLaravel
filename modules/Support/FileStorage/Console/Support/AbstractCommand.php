<?php

namespace Module\Support\FileStorage\Console\Support;

use Illuminate\Console\Command;
use Sdk\Module\Contracts\ModuleInterface;

abstract class AbstractCommand extends Command
{
    private ModuleInterface $module;

    protected function make(string $abstract)
    {
        if (!isset($this->module)) {
            $this->module = app()->module('FileStorage');
            $this->module->boot();
        }

        return $this->module->make($abstract);
    }
}
