<?php

namespace Shared\Support\Module;

use Illuminate\Container\Container;
use Illuminate\Contracts\Foundation\CachesConfiguration;
use Illuminate\Contracts\Foundation\CachesRoutes;

class Application extends Container implements CachesConfiguration, CachesRoutes
{
    use ApplicationTrait;

    public function __construct()
    {
        $this->bind('config', fn() => app('config'));
    }

    public function configurationIsCached()
    {
        return app()->configurationIsCached();
    }

    public function getCachedConfigPath()
    {
        return app()->getCachedConfigPath();
    }

    public function getCachedServicesPath()
    {
        return app()->getCachedServicesPath();
    }

    public function routesAreCached()
    {
        return app()->routesAreCached();
    }

    public function getCachedRoutesPath()
    {
        return app()->getCachedRoutesPath();
    }

    public function runningInConsole()
    {
        return app()->runningInConsole();
    }
}