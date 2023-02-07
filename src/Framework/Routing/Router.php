<?php

namespace Custom\Framework\Routing;

use Custom\Framework\Port\Request;

class Router
{
    private $routes = [];

    public function __construct(private $module) {}

    public function loadRoutes($path): void
    {
        include $path;
    }

    public function register(string $path, $action): static
    {
        $this->routes[$path] = $action;
        return $this;
    }

    public function request(string $path, array $attributes = [])
    {
        if (!isset($this->routes[$path])) {
            throw new \RuntimeException('Module path [' . $path . '] not found');
        }

        $request = new Request($path, $attributes);
        $action = $this->routes[$path];
        if (is_string($action)) {
            $action = $this->module->make($action);
            if (method_exists($action, 'handle')) {
                return $action->handle($request);
            } else {
                return $action($request);
            }
        } elseif (is_array($action)) {
            $controller = $this->module->make($action[0]);
            return $controller->{$action[1]}($request);
        } else {
            throw new \LogicException('Module route [' . $path . '] invalid');
        }
    }
}
