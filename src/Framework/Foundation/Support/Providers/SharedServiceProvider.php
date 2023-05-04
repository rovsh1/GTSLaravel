<?php

namespace Custom\Framework\Foundation\Support\Providers;

class SharedServiceProvider extends ServiceProvider
{
    private static array $sharedInstances = [];

    protected function sharedSingleton(string $alias, string $abstract): void
    {
        $this->app->singleton($alias, fn() => $this->getSharedInstance($alias, $abstract));
    }

    protected function getSharedInstance(string $alias, string $abstract)
    {
        return self::$sharedInstances[$alias] ?? (self::$sharedInstances[$alias] = $this->app->make($abstract));
    }
}
