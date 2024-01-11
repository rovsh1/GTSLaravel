<?php

namespace Pkg\Supplier\Traveline;

use Illuminate\Support\ServiceProvider;

class TravelineServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (!app()->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__ . '/../config/suppliers.php', 'suppliers');
        }
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootMigrations();
    }

    /**
     * Register Sanctum's migration files.
     *
     * @return void
     */
    protected function bootMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    /**
     * Register database migration paths.
     *
     * @param array|string $paths
     * @return void
     */
    protected function loadMigrationsFrom($paths)
    {
        $this->callAfterResolving('migrator', function ($migrator) use ($paths) {
            foreach ((array)$paths as $path) {
                $migrator->path($path);
            }
        });
    }
}
