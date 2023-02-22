<?php

namespace App\Core;

use App\Core\Support\ModulesRepository;
use Custom\Framework\Foundation\Module;

class Application extends \Illuminate\Foundation\Application
{
    private ModulesRepository $modules;

    public function __construct(private string $rootPath)
    {
        $this->modules = new ModulesRepository($this);

        parent::__construct();

        $this->useStoragePath($this->rootPath . DIRECTORY_SEPARATOR . 'storage');
        $this->useDatabasePath($this->rootPath . DIRECTORY_SEPARATOR . 'database');

        $this->bind('modules', fn($app) => $app->modules());
    }

    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;
    }

    public function rootPath($path = '')
    {
        return $this->rootPath . ($path != '' ? DIRECTORY_SEPARATOR . $path : '');
    }

    public function bootstrapPath($path = '')
    {
        return $this->rootPath . DIRECTORY_SEPARATOR . 'bootstrap' . ($path != '' ? DIRECTORY_SEPARATOR . $path : '');
    }

    public function configPath($path = '')
    {
        return $this->rootPath . DIRECTORY_SEPARATOR . 'config' . ($path != '' ? DIRECTORY_SEPARATOR . $path : '');
    }

    public function corePath($path = '')
    {
        return $this->rootPath . DIRECTORY_SEPARATOR . 'apps/core' . ($path != '' ? DIRECTORY_SEPARATOR . $path : '');
    }

    public function modulesPath($path = '')
    {
        return $this->rootPath . DIRECTORY_SEPARATOR . 'modules' . ($path != '' ? DIRECTORY_SEPARATOR . $path : '');
    }

    public function environmentPath()
    {
        return $this->rootPath;
    }

    public function module(string $name): ?Module
    {
        return $this->modules->get($name);
    }

    public function modules(): ModulesRepository
    {
        return $this->modules;
    }

    public function loadModule(string $name)
    {
        $this->modules->loadModule($name);
    }

    public function moduleLoaded(string $name): bool
    {
        return $this->modules->get($name)->isBooted();
    }

    protected function bindPathsInContainer()
    {
        parent::bindPathsInContainer();

        $this->instance('path.modules', $this->modulesPath());

        $appsPath = $this->rootPath . DIRECTORY_SEPARATOR . 'apps';
        $this->instance('path.admin', $appsPath . DIRECTORY_SEPARATOR . 'admin');
        $this->instance('path.core', $appsPath . DIRECTORY_SEPARATOR . 'core');
        $this->instance('path.site', $appsPath . DIRECTORY_SEPARATOR . 'site');
        $this->instance('path.api', $appsPath . DIRECTORY_SEPARATOR . 'api');
    }

    public function registerModules()
    {
        $this->modules->load(config('modules'));
    }
}
