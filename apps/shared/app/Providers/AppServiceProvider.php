<?php

namespace App\Shared\Providers;

use App\Shared\Components\Locale\Languages;
use Sdk\Module\Database\Eloquent\MacrosServiceProvider;
use Sdk\Module\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerServices();

        $this->app->register(DatabaseServiceProvider::class);
        $this->app->register(DateServiceProvider::class);
        $this->app->register(MacrosServiceProvider::class);
        $this->app->register(QueueServiceProvider::class);
        $this->app->register(ModuleServiceProvider::class);
        $this->app->register(TranslationServiceProvider::class);

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
