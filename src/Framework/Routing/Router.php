<?php

namespace Custom\Framework\Routing;

use Custom\Framework\Foundation\Module;

class Router
{
    private RoutesCollection $routesCollection;

    public function __construct(private readonly Module $module)
    {
        $this->routesCollection = new RoutesCollection();
    }

    public function registerRoute(Route $route): void
    {
        $this->routesCollection->add($route);
    }

    public function request(string $path, array $attributes = [])
    {
        $route = $this->routesCollection->findByPath($path);
        if (!$route) {
            throw new \RuntimeException('Module route [' . $path . '] not found');
        }

        return (new RouteHandler($this->module))->handle($route, $attributes);
    }
}
