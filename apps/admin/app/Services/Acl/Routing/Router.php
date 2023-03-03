<?php

namespace App\Admin\Services\Acl\Routing;

use App\Admin\Services\Acl\AccessControl;

class Router
{
    private RoutePermissions $routePermissions;

    public function __construct(private readonly AccessControl $accessControl)
    {
        $this->routePermissions = new RoutePermissions();
    }

    public function for(string $resource): RouteRegistrar
    {
        return new RouteRegistrar($this, $this->accessControl->getResource($resource));
    }

    public function resource(string $key, string $controller, array $options = []): RouteRegistrar
    {
        return $this->for($key)->resource($controller, $options);
    }

    public function assignRoute(string $routeName, string $permissionSlug): void
    {
        $this->routePermissions->assign($routeName, $permissionSlug);
    }

    public function isRouteAssigned(string $route): bool
    {
        return $this->routePermissions->has($route);
    }

    public function isRouteAllowed(string $route): bool
    {
        $permissionSlug = $this->routePermissions->get($route);
        if (!$permissionSlug) {
            return false;
        }

        return $this->accessControl->isAllowed($permissionSlug);
    }
}
