<?php

namespace Sdk\Module\Foundation;

use Illuminate\Contracts\Foundation\Application;
use Sdk\Module\Bus\IntegrationEventBus;
use Sdk\Module\Contracts\Api\ApiInterface;
use Sdk\Module\Contracts\Bus\IntegrationEventBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\Support\ModulesLoader;
use Sdk\Module\Foundation\Support\SharedContainer;

class SharedKernel
{
    protected Application $app;

    protected ModulesManager $modules;

    protected SharedContainer $sharedContainer;

    protected array $applicationBindings = [];

    public function __construct(Application $app, ModulesManager $modules)
    {
        $this->app = $app;
        $this->modules = $modules;
        $this->sharedContainer = $this->makeSharedContainer();
    }

    public function boot(): void
    {
        $this->registerRequiredDependencies();
        $this->registerApplicationBindings();
        $this->registerSharedBindings();
        $this->loadModules();
    }

    protected function registerSharedBindings(): void
    {
    }

    public function makeApi(string $abstract): ApiInterface
    {
        return $this->makeModuleAbstract($abstract);
    }

    public function makeUseCase(string $abstract): UseCaseInterface
    {
        return $this->makeModuleAbstract($abstract);
    }

    private function makeModuleAbstract(string $abstract)
    {
        $module = $this->modules->findByNamespace($abstract);
        if (!$module) {
            throw new \LogicException("Module not found by abstract [$abstract]");
        }
        $module->boot();

        return $this->sharedContainer->instance($abstract, $module->build($abstract));
    }

    protected function makeSharedContainer(): SharedContainer
    {
        return new SharedContainer();
    }

    protected function loadModules(): void
    {
        (new ModulesLoader(
            $this->modules,
            $this->sharedContainer
        ))->load($this->getModulesConfig());
    }

    protected function getModulesConfig(): array
    {
        return config('modules');
    }

    protected function registerRequiredDependencies(): void
    {
        $this->sharedContainer->instance(IntegrationEventBusInterface::class, new IntegrationEventBus());
    }

    protected function registerApplicationBindings(): void
    {
        foreach ($this->applicationBindings as $abstract) {
            $this->sharedContainer->bind($abstract, fn() => $this->app->get($abstract));
        }
    }
}
