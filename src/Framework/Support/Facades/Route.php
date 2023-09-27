<?php

namespace Custom\Framework\Support\Facades;

use Custom\Framework\Routing\RouteLoader;
use Custom\Framework\Routing\Route as RouteEntity;

class Route
{
    public static function register(string $path, $action): void
    {
        if (is_string($action)) {
            $action = [$action, $path];
        }

        $route = RouteEntity::fromPath($path, $action);

        RouteLoader::registerRoute($route);
    }
}