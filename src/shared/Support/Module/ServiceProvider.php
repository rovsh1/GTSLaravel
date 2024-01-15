<?php

namespace Shared\Support\Module;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected function loadMigrationsFrom($paths)
    {
        $this->rootCallAfterResolving('migrator', function ($migrator) use ($paths) {
            foreach ((array)$paths as $path) {
                $migrator->path($path);
            }
        });
    }

    protected function rootCallAfterResolving($name, $callback)
    {
        app()->afterResolving($name, $callback);

        if (app()->resolved($name)) {
            $callback(app()->make($name), $this->app);
        }
    }
}