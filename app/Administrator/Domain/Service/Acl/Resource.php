<?php

namespace GTS\Administrator\Domain\Service\Acl;

class Resource
{
    private readonly array $permissions;

    public function __construct(
        public readonly string $id,
        array $permissions,
    ) {
        $a = [];
        foreach ($permissions as $k => $v) {
            if (is_string($k))
                $a[$k] = (bool)$v;
            else
                $a[$v] = null;
        }
        $this->permissions = $a;
    }

    public function permissions(): array
    {
        return array_keys($this->permissions);
    }

    public function hasPermission(string $name): bool
    {
        return isset($this->permissions[$name]);
    }

    public function defaultRule(string $name): ?bool
    {
        return $this->permissions[$name];
    }
}
