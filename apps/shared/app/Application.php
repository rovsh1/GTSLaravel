<?php

namespace App\Shared;

use App\Shared\Contracts\Module\ModuleAdapterInterface;
use App\Shared\Support\Module\ModulesManager;
use App\Shared\Support\Module\Monolith\UseCaseWrapper;
use Sdk\Module\Contracts\Api\ApiInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

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

    public function modulesPath($path = ''): string
    {
        return $this->basePath('src/modules' . ($path != '' ? DIRECTORY_SEPARATOR . $path : ''));
    }

    public function module(string $name): ?ModuleAdapterInterface
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
            //@deprecated
            return (new UseCaseWrapper($this->modules()))->wrap($concrete);
        } elseif (is_subclass_of($concrete, UseCaseInterface::class)) {
            return (new UseCaseWrapper($this->modules()))->wrap($concrete);
        } else {
            return parent::build($concrete);
        }
    }

    private function registerModules(): void
    {
        $this->instance(ModulesManager::class, new ModulesManager());
        $this->alias(ModulesManager::class, 'modules');
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
