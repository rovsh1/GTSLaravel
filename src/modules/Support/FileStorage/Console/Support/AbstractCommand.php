<?php

namespace Module\Support\FileStorage\Console\Support;

use Illuminate\Console\Command;
use Sdk\Module\Contracts\Support\ContainerInterface;

abstract class AbstractCommand extends Command
{
    private ContainerInterface $container;

    protected function make(string $abstract)
    {
        if (!isset($this->container)) {
            $this->container = app()->module('FileStorage');
            $this->container->boot();
        }

        return $this->container->make($abstract);
    }
}
