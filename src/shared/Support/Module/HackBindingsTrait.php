<?php

namespace Shared\Support\Module;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Queue\QueueManager;

trait HackBindingsTrait
{
    private array $rootAbstracts = [
        'migrator',
        QueueManager::class,
        Schedule::class
    ];

    protected function registerHackBindings(): void
    {
        $this->bind('config', fn() => app('config'));
        foreach ($this->rootAbstracts as $key) {
            app()->afterResolving($key, fn($instance) => $this->fireAfterResolvingCallbacks($key, $instance));
        }
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