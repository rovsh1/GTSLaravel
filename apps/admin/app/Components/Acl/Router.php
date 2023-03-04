<?php

namespace App\Admin\Components\Acl;

use App\Admin\Components\Resource\ResourceManager;

class Router
{
    private array $permissions = [];

    public function __construct(
        private readonly ResourceManager $resources
    ) {}

    public function for(string $resource): RouteRegistrar
    {
        return new RouteRegistrar($this, $this->resources->getResource($resource));
    }

    public function resource(string $key, string $controller, array $options = []): RouteRegistrar
    {
        return $this->for($key)->resource($controller, $options);
    }

    public function assignRoute(string $routeName, string $permissionSlug): void
    {
        $this->permissions[$routeName] = $permissionSlug;
    }

    public function isRouteAssigned(string $route): bool
    {
        return isset($this->permissions[$route]);
    }

    public function getRoutePermission(string $route): ?string
    {
        return $this->permissions[$route] ?? null;
    }
}
