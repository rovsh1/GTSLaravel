<?php

namespace App\Admin\Components\Prototype;

class Prototype
{
    public readonly string $key;

    public readonly PrototypeGroups $group;

    protected array $permissions;

    protected array $config = [];

    public function __construct(array $config)
    {
        $this->key = $config['key'];
        $this->group = PrototypeGroups::from($config['group']);
        $this->permissions = $this->parsePermissions($config['permissions']);

        $config['routeUriPrefix'] = '/' . str_replace('.', '/', $config['key']);
        $config['routeNamePrefix'] = $config['key'] . '.';

        $this->config = $config;
    }

    public function config(string $key = null)
    {
        return null === $key ? $this->config : ($this->config[$key] ?? null);
    }

    public function is($resource): bool
    {
        return $resource === $this
            || $resource === $this->key
            || $resource === $this->config('alias');
    }

    public function makeRepository(): PrototypeRepositoryInterface
    {
        return isset($this->config['repository'])
            ? app()->make($this->config['repository'])
            : new DefaultRepository($this->config['model']);
    }

    public function title(string $key): ?string
    {
        return $this->config['titles'][$key] ?? null;
    }

    public function view(string $key): ?string
    {
        return $this->config['views'][$key] ?? null;
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
