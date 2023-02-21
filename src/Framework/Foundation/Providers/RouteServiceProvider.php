<?php

namespace Custom\Framework\Foundation\Providers;

use Custom\Framework\Foundation\Support\Providers\ServiceProvider;
use Custom\Framework\Routing\RouteLoader;
use Custom\Framework\Routing\Router;

class RouteServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('router', function ($module) {
            $router = new Router($module);

            (new RouteLoader($router))->load($module->path('Port/routes.php'));

            return $router;
        });
    }
}
