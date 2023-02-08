<?php

namespace Custom\Framework\Routing;

class RouteLoader
{
    private static ?Router $routerInstance;

    public function __construct(Router $router)
    {
        self::$routerInstance = $router;
    }

    public function load(string $routes): void
    {
        require $routes;

        self::$routerInstance = null;
    }

    public static function registerRoute(Route $route): void
    {
        self::$routerInstance->registerRoute($route);
    }
}
