<?php

namespace GTS\Shared\Custom\Foundation\Providers;

use GTS\Shared\Infrastructure\Adapter\PortGatewayInterface;
use GTS\Shared\Infrastructure\Support\ServiceProvider;
use GTS\Shared\UI\Port\Routing\Router;

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
