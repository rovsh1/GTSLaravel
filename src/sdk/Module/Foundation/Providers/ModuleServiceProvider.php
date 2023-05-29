<?php

namespace Sdk\Module\Foundation\Providers;

use Illuminate\Support\ServiceProvider;
use Sdk\Module\Bus\IntegrationEventBus;
use Sdk\Module\Foundation\ModulesManager;
use Sdk\Module\Foundation\Support\ModulesLoader;
use Sdk\Module\Foundation\Support\SharedContainer;

class ModuleServiceProvider extends ServiceProvider
{
    protected array $sharedBindings = [];

    protected function registerModulesDependencies(ModulesManager $modules): void
    {
        $sharedContainer = new SharedContainer(
            $modules,
            new IntegrationEventBus()
        );
        $this->registerSharedBindings($sharedContainer);
        $this->app->instance(SharedContainer::class, $sharedContainer);

        (new ModulesLoader(
            $modules,
            $sharedContainer
        ))->load($this->getModulesConfig());
    }

    protected function getModulesConfig(): array
    {
        return config('modules');
    }

    protected function registerSharedBindings(SharedContainer $sharedContainer): void
    {
        foreach ($this->sharedBindings as $abstract) {
            $sharedContainer->bind($abstract, fn() => $this->app->get($abstract));
        }
    }
}
