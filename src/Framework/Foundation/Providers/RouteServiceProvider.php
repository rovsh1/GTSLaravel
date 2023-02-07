<?php

namespace Custom\Framework\Foundation\Providers;

use Custom\Framework\Foundation\Support\ServiceProvider;
use Custom\Framework\Routing\Router;

class RouteServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('router', function ($module) {
            $router = new Router($module);

            $router->loadRoutes($module->path('UI/Port/routes.php'));

            return $router;
        });
    }
}
