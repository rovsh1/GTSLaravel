<?php

namespace Custom\Framework\Foundation\Providers;

use Custom\Framework\Routing\Router;
use GTS\Shared\Infrastructure\Adapter\PortGatewayInterface;
use GTS\Shared\Infrastructure\Support\ServiceProvider;

class PortServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(PortGatewayInterface::class, fn() => app(PortGatewayInterface::class));

        $this->app->singleton('router', function ($module) {
            $router = new Router($module);

            $router->loadRoutes($module->path('UI/Port/routes.php'));

            return $router;
        });
    }
}
