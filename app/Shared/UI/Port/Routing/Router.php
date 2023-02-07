<?php

namespace GTS\Shared\UI\Port\Routing;

use GTS\Shared\Custom\Foundation\Module;

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

    public function request(string $path, array $attributes = []): mixed
    {
        if (!isset($this->routes[$path])) {
            throw new \RuntimeException('Module route [' . $path . '] not found');
        }

        $action = $this->module->make($this->routes[$path]);

        return $action->handle((object)$attributes);
    }
}
