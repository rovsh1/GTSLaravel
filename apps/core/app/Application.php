<?php

namespace App\Core;

use Module\SharedKernel;
use Sdk\Module\Contracts\Api\ApiInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Module;
use Sdk\Module\Foundation\ModulesManager;
use Sdk\Module\Foundation\Support\ModulesLoader;

class Application extends \Illuminate\Foundation\Application
{
    private ?string $packagePath = null;

    public function __construct(string $basePath = null)
    {
        parent::__construct($basePath);

        $this->registerModules();
        $this->registerSharedKernel();

//        $this->useStoragePath($this->basePath('storage'));
//        $this->useDatabasePath($this->basePath('database'));
    }

    public function usePackagePath(string $path): void
    {
        $this->packagePath = $path;
        $this->useAppPath($path . DIRECTORY_SEPARATOR . 'app');
        $this->usePublicPath($path . DIRECTORY_SEPARATOR . 'public');
        $this->useLangPath($path . DIRECTORY_SEPARATOR . 'resources/lang');
    }

    public function resourcePath($path = ''): string
    {
        return $this->joinPaths($this->packagePath . DIRECTORY_SEPARATOR . 'resources', $path);
    }

//    public function viewPath($path = ''){}

    public function packagePath($path = ''): string
    {
        return $this->joinPaths($this->packagePath, $path);
    }

    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    public function corePath($path = ''): string
    {
        return $this->basePath('apps/core' . ($path != '' ? DIRECTORY_SEPARATOR . $path : ''));
    }

    public function modulesPath($path = ''): string
    {
        return $this->basePath('modules' . ($path != '' ? DIRECTORY_SEPARATOR . $path : ''));
    }

    public function module(string $name): ?Module
    {
        return $this->modules->get($name);
    }

    public function modules(): ModulesManager
    {
        return $this->instances[ModulesManager::class];
    }

    public function build($concrete)
    {
        if (!is_string($concrete)) {
            return parent::build($concrete);
        } elseif (is_subclass_of($concrete, ApiInterface::class)) {
            return $this->makeModuleAbstract($concrete);
        } elseif (is_subclass_of($concrete, UseCaseInterface::class)) {
            return $this->makeModuleAbstract($concrete);
        } else {
            return parent::build($concrete);
        }
    }

    private function makeModuleAbstract(string $abstract)
    {
        $module = $this->modules()->findByNamespace($abstract);
        if (!$module) {
            throw new \LogicException("Module not found by abstract [$abstract]");
        }
        $module->boot();

        return $this->instances[SharedKernel::class]->getContainer()->instance($abstract, $module->build($abstract));
    }

    private function registerModules(): void
    {
        $this->instance(
            ModulesManager::class,
            new ModulesManager(
                modulesNamespace: 'Module',
                modulesPath: $this->basePath('modules')
            )
        );
        $this->alias(ModulesManager::class, 'modules');

        $this->booting(function () {
            $this->loadModules();
        });
    }

    private function registerSharedKernel(): void
    {
        $kernel = new SharedKernel($this);
        $this->instance(SharedKernel::class, $kernel);
        $this->booting(function () {
            $this->instances[SharedKernel::class]->boot();
        });
    }

    private function loadModules(): void
    {
        (new ModulesLoader(
            $this->modules(),
            $this->instances[SharedKernel::class]->getContainer()
        ))->load(config('modules'));
    }

    protected function bindPathsInContainer()
    {
        parent::bindPathsInContainer();

        $this->instance('path.modules', $this->modulesPath());

        $appsPath = $this->basePath('apps');
        $this->instance('path.admin', $appsPath . DIRECTORY_SEPARATOR . 'admin');
        $this->instance('path.core', $appsPath . DIRECTORY_SEPARATOR . 'core');
        $this->instance('path.site', $appsPath . DIRECTORY_SEPARATOR . 'site');
        $this->instance('path.api', $appsPath . DIRECTORY_SEPARATOR . 'api');
//        $this->instance('path.package', $this->packagePath);
    }
}
