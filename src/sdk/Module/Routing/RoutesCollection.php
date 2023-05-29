<?php

namespace Sdk\Module\Routing;

class RoutesCollection
{
    private array $routes = [];

    public function add(Route $route): void
    {
        $this->routes[] = $route;
    }

    public function findByPath(string $path): ?Route
    {
        foreach ($this->routes as $route) {
            if ($route->path() === $path) {
                return $route;
            }
        }
        return null;
    }
}
