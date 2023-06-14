<?php

namespace App\Core;

use App\Core\Providers\ModuleServiceProvider;
use Module\SharedKernel;
use Sdk\Module\Contracts\Api\ApiInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Module;
use Sdk\Module\Foundation\ModulesManager;

class Application extends \Illuminate\Foundation\Application
{
    public function __construct(private string $rootPath)
    {
        parent::__construct();

        $this->instance(
            ModulesManager::class,
            new ModulesManager(
                'Module',
                $this->rootPath('modules')
            )
        );
        $this->alias(ModulesManager::class, 'modules');

        $this->registerRequiredServiceProviders();

        $this->useStoragePath($this->rootPath . DIRECTORY_SEPARATOR . 'storage');
        $this->useDatabasePath($this->rootPath . DIRECTORY_SEPARATOR . 'database');
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

    public function modules(): ModulesManager
    {
        return $this->instances[ModulesManager::class];
    }

    public function registerModule(Module $module): void
    {
        $this->modules->registerModule($module);
    }

    public function loadModule(string $name): void
    {
        $this->modules->loadModule($name);
    }

    public function moduleLoaded(string $name): bool
    {
        return $this->modules->get($name)->isBooted();
    }

    public function build($concrete)
    {
        if (!is_string($concrete)) {
            return parent::build($concrete);
        } elseif (is_subclass_of($concrete, ApiInterface::class)) {
            return $this->instances[SharedKernel::class]->makeApi($concrete);
        } elseif (is_subclass_of($concrete, UseCaseInterface::class)) {
            return $this->instances[SharedKernel::class]->makeUseCase($concrete);
        } else {
            return parent::build($concrete);
        }
    }

    private function registerRequiredServiceProviders()
    {
        $this->register(ModuleServiceProvider::class);
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
}
