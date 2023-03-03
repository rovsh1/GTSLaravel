<?php

namespace App\Admin\Services\Acl\Routing;

class RoutePermissions
{
    private array $routes = [];

    public function assign(string $route, string $permissionSlug): void
    {
        $this->routes[$route] = $permissionSlug;
    }

    public function has(string $route): bool
    {
        return isset($this->routes[$route]);
    }

    public function get(string $route): ?string
    {
        return $this->routes[$route] ?? null;
    }
}
