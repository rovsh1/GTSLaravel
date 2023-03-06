<?php

namespace App\Admin\Components\Acl;

class Resource
{
    public readonly string $key;

    protected array $permissions;

    protected array $config = [];

    public function __construct(array $config)
    {
        $this->key = $config['key'];
        $this->permissions = $this->parsePermissions($config['permissions']);

        $config['routeUriPrefix'] = '/' . str_replace('.', '/', $config['key']);
        $config['routeNamePrefix'] = $config['key'] . '.';

        $this->config = $config;
    }

    public function config(string $key)
    {
        return $this->config[$key] ?? null;
    }

    public function routeName(string $method = null): string
    {
        return $this->config['routeNamePrefix'] . $method;
    }

    public function routePrefix(string $uri = null): string
    {
        return $this->config['routeUriPrefix'] . $uri;
    }

    public function route(string $method, $params = []): string
    {
        return route($this->routeName($method), $params);
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
        return $this->routeName($permission);
    }

    private function parsePermissions($value): array
    {
        if ($value === 'crud') {
            return ['create', 'read', 'update', 'delete'];
        } elseif (is_string($value)) {
            return [$value => null];
        } elseif (is_array($value)) {
            return $value;
        } else {
            return [];
        }
    }
}
