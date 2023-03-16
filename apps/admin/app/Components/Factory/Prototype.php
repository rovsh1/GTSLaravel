<?php

namespace App\Admin\Components\Factory;

use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Repository\RepositoryInterface;

class Prototype
{
    public readonly string $key;

    public readonly string $category;

    public readonly string $group;

    //public readonly PrototypeTypes $type;

    protected array $config = [];

    public function __construct(array $config)
    {
        $this->key = $config['key'];
        $this->category = $config['category'];
        $this->group = $config['group'] ?? 'main';
        //$this->type = PrototypeTypes::from($config['type'] ?? 'main');

        $config['route'] = $config['route'] ?? $config['key'];

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

    public function makeRepository(): RepositoryInterface
    {
        if (isset($this->config['repository'])) {
            return app()->make($this->config['repository']);
        } elseif (isset($this->config['model'])) {
            return new DefaultRepository($this->config['model']);
        } else {
            throw new \LogicException('Prototype [' . $this->key . '] repository undefined');
        }
    }

    public function title(string $key = 'index'): ?string
    {
        return $this->config['titles'][$key] ?? null;
    }

    public function view(string $key): ?string
    {
        return $this->config['views'][$key] ?? null;
    }

    public function routeName(string $method = null): string
    {
        return $this->config['route'] . '.' . $method;
    }

    public function route(string $method = 'index', $params = []): string
    {
        return route($this->routeName($method), $params);
    }

    public function permissions(): array
    {
        return Acl::resource($this->key)->permissions();
    }

    public function hasPermission(string $name): bool
    {
        return in_array($name, $this->permissions());
    }

    public function permissionSlug(string $permission): string
    {
        return Acl::resource($this->key)->permissionSlug($permission);
    }
}
