<?php

namespace App\Admin\Services\Acl;

class Rules
{
    private array $permissions = [];

    private bool $superuser = false;

    public function __construct(
        private readonly AccessControl $acl
    ) {}

    public function superuser(bool $flag): void
    {
        $this->superuser = $flag;
    }

    public function allow(string $resourceId, string $permission = null): void
    {
        $this->setPermission($resourceId, $permission, true);
    }

    public function deny(string $resourceId, array $permission = null): void
    {
        $this->setPermission($resourceId, $permission, false);
    }

    public function setPermission(string $resourceId, ?string $permission, bool $flag): void
    {
        $resource = $this->acl->resources()->get($resourceId);
        if (!$resource)
            throw new \Exception('Resource [' . $resourceId . '] undefined');

        elseif (!$resource->hasPermission($permission))
            throw new \Exception('Resource [' . $resourceId . '] permission [' . $permission . '] undefined');

        if (!isset($this->permissions[$resourceId]))
            $this->permissions[$resourceId] = [];

        if (null === $permission) {
            foreach ($resource->permissions() as $permission) {
                $this->permissions[$resourceId][$permission] = $flag;
            }
        } else
            $this->permissions[$resourceId][$permission] = $flag;
    }

    public function isAllowed(string $resourceId, string $permission): bool
    {
        if ($this->superuser)
            return true;

        $resource = $this->acl->resources()->get($resourceId);
        if (!$resource)
            return false;

        if (isset($this->permissions[$resourceId][$permission]))
            return $this->permissions[$resourceId][$permission];

        return (bool)$resource->defaultRule($permission);
    }
}
