<?php

namespace App\Admin\Components\Acl;

use Illuminate\Routing\RouteRegistrar as BaseRegistrar;

class Router
{
    private RoutesCollection $routes;

    private array $permissions = [];

    public function __construct(
        private readonly ResourcesCollection $resources
    ) {
        $this->routes = new RoutesCollection($this);
    }

    public function for(string $resource): RouteResourceRegistrar
    {
        if (!$this->resources->has($resource)) {
            throw new \Exception('Resource [' . $resource . '] undefined');
        }

        return new RouteResourceRegistrar($this->routes, $this->resources->get($resource));
    }

    public function resource(string $key, string $controller, array $options = []): RouteResourceRegistrar
    {
        return $this->for($key)->resource('', $controller, $options);
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

    public function registerRoutes(BaseRegistrar $routeRegistrar): void
    {
        $routeRegistrar
            ->group(function () {
                $this->routes->register();
                unset($this->routes);
            });
    }
}
