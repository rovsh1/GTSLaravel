<?php

namespace App\Admin\Components\Acl;

class Permissions
{
    private array $permissions = [];

    private bool $superuser = false;

    public function __construct(
        private readonly ResourcesCollection $resources
    ) {}

    public function superuser(bool $flag): void
    {
        $this->superuser = $flag;
    }

    public function allow(string $resource, string $permission = null): void
    {
        $this->setPermission($resource, $permission, true);
    }

    public function deny(string $resource, string $permission = null): void
    {
        $this->setPermission($resource, $permission, false);
    }

    public function isAllowed(string $resource, string $permission = null): bool
    {
        if ($this->superuser) {
            return true;
        }

        if (null === $permission) {
            [$resource, $permission] = $this->parseSlug($resource);
        }

        if (!$this->resources->has($resource)) {
            return false;
        }

        return $this->permissions[$resource][$permission] ?? false;
    }

    private function parseSlug(string $slug): array
    {
        if (str_contains($slug, ' ')) {
            return array_reverse(explode(' ', $slug, 2));
        } elseif (str_contains($slug, '.')) {
            $segments = explode('.', $slug);
            $permission = array_pop($segments);
            return [implode('.', $segments), $permission];
        } else {
            return [null, $slug];
        }
    }

    private function setPermission(string $resourceKey, ?string $permission, bool $flag): void
    {
        if (null === $permission) {
            [$resourceKey, $permission] = $this->parseSlug($resourceKey);
        }

        $resource = $this->resources->get($resourceKey);
        if (!$resource) {
            throw new \Exception('Acl resource [' . $resourceKey . '] undefined');
        } elseif (!$resource->hasPermission($permission)) {
            throw new \Exception('Acl resource [' . $resourceKey . '] permission undefined');
        }

        $this->permissions[$resource->key][$permission] = $flag;
    }
}
