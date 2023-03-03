<?php

namespace App\Admin\Services\Acl;

use App\Admin\Enums\ResourceGroup;

class Resource
{
    public readonly string $name;

    public readonly string $key;

    public readonly ResourceGroup $group;

    private array $permissions;

    private array $config;

    public function __construct(array $config)
    {
        $this->name = $config['name'];
        $this->key = $config['key'];
        $this->group = ResourceGroup::from($config['group']);
        $this->permissions = $this->parsePermissions($config['permissions']);

        $config['routeUriPrefix'] = '/' . str_replace('.', '/', $config['key']);
        $config['routeNamePrefix'] = $config['key'] . '.';

        $this->config = $config;
    }

    public function repository(): string
    {
        return $this->repository;
    }

    public function title(string ...$keys): ?string
    {
        foreach ($keys as $key) {
            if (isset($this->config['titles'][$key])) {
                return $this->config['titles'][$key];
            }
        }
        return null;
    }

    public function view(string ...$keys): ?string
    {
        foreach ($keys as $key) {
            if (isset($this->config['views'][$key])) {
                return $this->config['views'][$key];
            }
        }
        return null;
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
        return array_keys($this->permissions);
    }

    public function hasPermission(string $name): bool
    {
        return array_key_exists($name, $this->permissions);
    }

    public function permissionSlug(string $permission): string
    {
        return $this->routeName($permission);
    }

    public function defaultRule(string $name): ?bool
    {
        return $this->permissions[$name];
    }

    private function parsePermissions($value): array
    {
        if ($value === 'crud') {
            return ['create' => null, 'read' => null, 'update' => null, 'delete' => null];
        } elseif (is_string($value)) {
            return [$value => null];
        } elseif (is_array($value)) {
            $a = [];
            foreach ($value as $k => $v) {
                if (is_string($k)) {
                    $a[$k] = (bool)$v;
                } else {
                    $a[$v] = null;
                }
            }
            return $a;
        } else {
            return [];
        }
    }
}
