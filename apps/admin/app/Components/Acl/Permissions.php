<?php

namespace App\Admin\Components\Acl;

class Permissions
{
    private array $permissions = [];

    private bool $superuser = false;

    public function superuser(bool $flag): void
    {
        $this->superuser = $flag;
    }

    public function allow(string $permission): void
    {
        $this->setPermission($permission, true);
    }

    public function deny(string $permission): void
    {
        $this->setPermission($permission, false);
    }

    public function setPermission(string $permission, bool $flag): void
    {
        $this->permissions[$this->parseSlug($permission)] = $flag;
    }

    public function isAllowed(string $permission): bool
    {
        if ($this->superuser) {
            return true;
        }

        return $this->permissions[$this->parseSlug($permission)] ?? false;
    }

    public function parseSlug(string $slug): string
    {
        if (str_contains($slug, ' ')) {
            return implode('.', array_reverse(explode(' ', $slug, 2)));
        } else {
            return $slug;
        }
    }

}
