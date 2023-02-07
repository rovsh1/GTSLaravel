<?php

namespace Custom\Framework\Routing;

use Custom\Framework\Foundation\Module;

class Router
{
    private $routes = [];

    public function __construct(private readonly Module $module) {}

    public function loadRoutes($path): void
    {
        include $path;
    }

    public function register(string $path, string $action): static
    {
        $this->routes[$path] = $action;
        return $this;
    }

    public function get(string $path): ?string
    {
        return $this->routes[$path] ?? null;
    }
}
