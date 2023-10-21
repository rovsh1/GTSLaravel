<?php

namespace Sdk\Module\Foundation\Providers;

use Sdk\Module\Routing\RouteLoader;
use Sdk\Module\Routing\Router;

class RouteServiceProvider extends \Sdk\Module\Support\ServiceProvider
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
