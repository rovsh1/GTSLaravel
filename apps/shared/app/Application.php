<?php

namespace App\Shared;

use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Shared\Support\Module\Module;
use Shared\Support\Module\ModuleRepository;
use Shared\Support\Module\UseCaseWrapper;

class Application extends \Illuminate\Foundation\Application
{
    private ?string $packagePath = null;

    public function __construct(string $basePath = null)
    {
        parent::__construct($basePath);

        $this->registerModules();

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
        $basePath = $this->packagePath !== null ? $this->packagePath : $this->basePath;

        return $this->joinPaths($basePath . DIRECTORY_SEPARATOR . 'resources', $path);
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

    public function modulesPath($path = ''): string
    {
        return $this->basePath('src/modules' . ($path != '' ? DIRECTORY_SEPARATOR . $path : ''));
    }

    public function module(string $name): ?Module
    {
        return $this->modules->get($name);
    }

    public function modules(): ModuleRepository
    {
        return $this->instances[ModuleRepository::class];
    }

    public function build($concrete)
    {
        if (!is_string($concrete)) {
            return parent::build($concrete);
        } elseif (is_subclass_of($concrete, UseCaseInterface::class)) {
            return (new UseCaseWrapper($this->modules()))->wrap($concrete);
        } elseif ($this->isModuleInstance($concrete)) {
            //HACK т. к. контроллеры модулей создаются из основного приложения
            $module = $this->modules()->findByNamespace($concrete);
            if (!$module) {
                throw new \LogicException("Module with namespace $concrete not found");
            }
            $module->boot();

            return $module->get($concrete);
        } else {
            return parent::build($concrete);
        }
    }

    private function isModuleInstance(string $concrete): bool
    {
        return str_starts_with($concrete, 'Pkg\\') || str_starts_with($concrete, 'Module\\');
    }

    private function registerModules(): void
    {
        $this->instance(ModuleRepository::class, new ModuleRepository());
        $this->alias(ModuleRepository::class, 'modules');
    }

    protected function bindPathsInContainer()
    {
        parent::bindPathsInContainer();

        $this->instance('path.modules', $this->modulesPath());

        $appsPath = $this->basePath('apps');
        $this->instance('path.admin', $appsPath . DIRECTORY_SEPARATOR . 'admin');
        $this->instance('path.site', $appsPath . DIRECTORY_SEPARATOR . 'site');
        $this->instance('path.api', $appsPath . DIRECTORY_SEPARATOR . 'api');
    }
}
