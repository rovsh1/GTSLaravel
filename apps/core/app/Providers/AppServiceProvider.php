<?php

namespace App\Core\Providers;

use App\Core\Components\Context\AppContext;
use App\Core\Components\Locale\Languages;
use Custom\Framework\Database\Eloquent\MacrosServiceProvider;
use Custom\Framework\Foundation\Support\Providers\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerServices();

        $this->app->register(DatabaseServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(NotificationServiceProvider::class);
        $this->app->register(DateServiceProvider::class);
        $this->app->register(MacrosServiceProvider::class);
        $this->app->register(PortGatewayServiceProvider::class);
        $this->app->register(QueueServiceProvider::class);
        $this->app->register(ModulesServiceProvider::class);

        $this->registerApp();

        $this->registerComponents();
    }

    private function registerServices()
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
        $this->app->register(\Spatie\LaravelData\LaravelDataServiceProvider::class);
    }

    private function registerApp()
    {
        $this->app->singleton('app-context', AppContext::class);

        $namespace = $this->app->getNamespace();
        if ($namespace && class_exists($namespace . 'Providers\BootServiceProvider')) {
            $this->app->register($namespace . 'Providers\BootServiceProvider');
        }
    }

    private function registerComponents()
    {
        $this->app->singleton('languages', fn($app) => new Languages($app['config']['languages']));
    }
}
