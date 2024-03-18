<?php

namespace App\Shared\Providers;

use App\Shared\Components\Locale\Languages;
use App\Shared\Logging\LogServiceProvider;
use Illuminate\Support\ServiceProvider;
use Sdk\Module\Database\Eloquent\MacrosServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerServices();

        $this->app->register(LogServiceProvider::class);
        $this->app->register(DatabaseServiceProvider::class);
        $this->app->register(DateServiceProvider::class);
        $this->app->register(MacrosServiceProvider::class);
        $this->app->register(CoreServiceProvider::class);
        $this->app->register(ModuleServiceProvider::class);
        $this->app->register(DropboxServiceProvider::class);

        $this->registerApp();

        $this->registerComponents();
    }

    private function registerServices(): void
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    private function registerApp(): void
    {
        $namespace = $this->app->getNamespace();
        if ($namespace && class_exists($namespace . 'Providers\BootServiceProvider')) {
            $this->app->register($namespace . 'Providers\BootServiceProvider');
        }
    }

    private function registerComponents(): void
    {
        $this->app->singleton('languages', fn($app) => new Languages($app['config']['languages']));
    }
}
