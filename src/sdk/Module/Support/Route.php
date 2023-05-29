<?php

namespace Sdk\Module\Support;

use Sdk\Module\Routing\Route as RouteEntity;
use Sdk\Module\Routing\RouteLoader;

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
