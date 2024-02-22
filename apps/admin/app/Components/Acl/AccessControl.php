<?php

namespace App\Admin\Components\Acl;

class AccessControl
{
    private readonly Permissions $permissions;

    private readonly Router $router;

    private ResourcesCollection $resources;

    public function __construct()
    {
        $this->resources = new ResourcesCollection();
        $this->permissions = new Permissions($this->resources);
        $this->router = new Router($this->resources);
    }

    public function permissions(): Permissions
    {
        return $this->permissions;
    }

    public function resources(): ResourcesCollection
    {
        return $this->resources;
    }

    public function resource(string $name): ?Resource
    {
        return $this->resources->get($name);
    }

    public function router(): Router
    {
        return $this->router;
    }

    public function isSuperuser(): bool
    {
        return $this->permissions->isSuperuser();
    }

    public function isAllowed(string $resource, string $permission = null): bool
    {
        return $this->permissions->isAllowed($resource, $permission);
    }

    public function isReadAllowed(string $resource): bool
    {
        return $this->isAllowed($resource, 'read');
    }

    public function isCreateAllowed(string $resource): bool
    {
        return $this->isAllowed($resource, 'create');
    }

    public function isUpdateAllowed(string $resource): bool
    {
        return $this->isAllowed($resource, 'update');
    }

    public function isDeleteAllowed(string $resource): bool
    {
        return $this->isAllowed($resource, 'delete');
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
