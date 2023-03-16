<?php

namespace App\Admin\Components\Acl;

class Resource
{
    public readonly string $key;

    public readonly string $route;

    protected array $permissions;

    protected array $config = [];

    public function __construct(array $config)
    {
        $this->key = $config['key'];
        $this->permissions = $this->parsePermissions($config['permissions']);
        $this->config = $config;
        $this->route = $config['route'] ?? $config['key'];
    }

    public function config(string $key)
    {
        return $this->config[$key] ?? null;
    }

    public function permissions(): array
    {
        return $this->permissions;
    }

    public function hasPermission(string $name): bool
    {
        return in_array($name, $this->permissions);
    }

    public function permissionSlug(string $permission): string
    {
        return $permission . ' ' . $this->key;
    }

    private function parsePermissions($value): array
    {
        if ($value === 'crud') {
            return ['read', 'update', 'create', 'delete'];
        } elseif (is_string($value)) {
            return [$value => null];
        } elseif (is_array($value)) {
            return $value;
        } else {
            return [];
        }
    }
}
