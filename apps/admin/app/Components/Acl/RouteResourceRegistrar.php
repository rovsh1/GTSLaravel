<?php

namespace App\Admin\Components\Acl;

class RouteResourceRegistrar
{
    public function __construct(
        private readonly RoutesCollection $routes,
        private readonly Resource $resource
    ) {}

    public function get(string $uri, string $action, string $permission, string $name): static
    {
        return $this->addAction(['GET'], $uri, $action, $permission, $name);
    }

    public function post(string $uri, string $action, string $permission, string $name): static
    {
        return $this->addAction(['POST'], $uri, $action, $permission, $name);
    }

    public function put(string $uri, string $action, string $permission, string $name): static
    {
        return $this->addAction(['PUT'], $uri, $action, $permission, $name);
    }

    public function patch(string $uri, string $action, string $permission, string $name): static
    {
        return $this->addAction(['PATCH'], $uri, $action, $permission, $name);
    }

    public function delete(string $uri, string $action, string $permission, string $name): static
    {
        return $this->addAction(['DELETE'], $uri, $action, $permission, $name);
    }

    public function options(string $uri, string $action, string $permission, string $name): static
    {
        return $this->addAction(['OPTIONS'], $uri, $action, $permission, $name);
    }

    public function any(string $uri, string $action, string $permission, string $name): static
    {
        return $this->addAction(\Illuminate\Routing\Router::$verbs, $uri, $action, $permission, $name);
    }

    public function match(array $methods, string $uri, string $action, string $permission, string $name): static
    {
        return $this->addAction($methods, $uri, $action, $permission, $name);
    }

    public function addAction(array $methods, string $uri, string $action, string $permission, string $name): static
    {
        $this->routes->addAction($methods, $uri, $action, $this->resource->permissionSlug($permission), $name);
        return $this;
    }

    public function resource(string $name, string $controller, array $options = []): static
    {
        $methods = ['index'];

        if ($this->resource->hasPermission('create')) {
            $methods = array_merge($methods, ['create', 'store']);
        }

        if ($this->resource->hasPermission('update')) {
            $methods = array_merge($methods, ['edit', 'update']);
        }

        $methods[] = 'show';

        if ($this->resource->hasPermission('delete')) {
            $methods = array_merge($methods, ['destroy']);
        }

        if (isset($options['only'])) {
            $methods = array_intersect($methods, (array)$options['only']);
        }

        if (isset($options['except'])) {
            $methods = array_diff($methods, (array)$options['except']);
        }

        $resourceSlug = self::formatSlug($this->resource->key);
        $prefix = '/' . $this->resource->route;
        $namePrefix = $this->resource->route . '.';

        if ($name) {
            $base = self::formatSlug($name);
            $prefix .= '/{' . $resourceSlug . '}/' . $name;
            $namePrefix .= $name . '.';
        } else {
            $base = $resourceSlug;
        }

        foreach ($methods as $method) {
            $this->{'addResource' . ucfirst($method)}($prefix, $namePrefix, $base, $controller);
        }

        return $this;
    }

    private function addResourceIndex($path, $name, $base, $controller): void
    {
        $this->get($path, $controller . '@index', 'read', $name . 'index');
    }

    private function addResourceShow($path, $name, $base, $controller): void
    {
        $this->get($path . '/{' . $base . '}', $controller . '@show', 'read', $name . 'show');
    }

    private function addResourceCreate($path, $name, $base, $controller): void
    {
        $this->get($path . '/create', $controller . '@create', 'create', $name . 'create');
    }

    private function addResourceStore($path, $name, $base, $controller): void
    {
        $this->post($path, $controller . '@store', 'create', $name . 'store');
    }

    private function addResourceEdit($path, $name, $base, $controller): void
    {
        $this->get($path . '/{' . $base . '}/edit', $controller . '@edit', 'update', $name . 'edit');
    }

    private function addResourceUpdate($path, $name, $base, $controller): void
    {
        $this->match(['PUT', 'PATCH'], $path . '/{' . $base . '}', $controller . '@update', 'update', $name . 'update');
    }

    private function addResourceDestroy($path, $name, $base, $controller): void
    {
        $this->delete($path . '/{' . $base . '}', $controller . '@destroy', 'delete', $name . 'destroy');
    }

    private static function formatSlug(string $slug): string
    {
        return str_replace('-', '_', $slug);
    }
}
