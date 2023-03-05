<?php

namespace App\Admin\Components\Acl;

use App\Admin\Components\Source\SourceManager;

class AccessControl
{
    private readonly Permissions $permissions;
    private readonly Router $router;

    public function __construct(private readonly SourceManager $resources)
    {
        $this->permissions = new Permissions();
        $this->router = new Router($this->resources);
    }

    public function permissions(): Permissions
    {
        return $this->permissions;
    }

    public function router(): Router
    {
        return $this->router;
    }

    public function isAllowed(string $permission = null): bool
    {
        return $this->permissions->isAllowed($permission);
    }

    public function isRouteAssigned(string $route): bool
    {
        return $this->router->isRouteAssigned($route);
    }

    public function isRouteAllowed(string $route): bool
    {
        $permission = $this->router->getRoutePermission($route);
        if (null === $permission) {
            return false;
        }

        return $this->isAllowed($permission);
    }

    public function setUser($user)
    {
        //$this->rules
    }
}
