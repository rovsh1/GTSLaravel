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
        $this->routes->addAction(
            $methods,
            '/' . $this->resource->route . $uri,
            $action,
            $this->resource->permissionSlug($permission),
            $this->resource->route . '.' . $name
        );
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

        $resourceSlug = self::formatSlug($options['slug'] ?? $this->resource->key);
        $prefix = '';
        $namePrefix = '';

        if ($name) {
            [$prefix, $base] = $this->getResourcePrefixAndBase($name);
            $prefix = '/{' . $resourceSlug . '}/' . $prefix;
            $namePrefix .= $name . '.';
        } else {
            $base = $resourceSlug;
        }

        foreach ($methods as $method) {
            $this->{'addResource' . ucfirst($method)}($prefix, $namePrefix, $base, $controller);
        }

        return $this;
    }

    private function getResourcePrefixAndBase(string $name): array
    {
        $prefix = '';
        $preparedName = $name;
        $base = self::formatSlug($options['parameters'][$name] ?? $name);

        $nameParts = explode('.', $preparedName);
        if (count($nameParts) > 1) {
            $preparedName = array_shift($nameParts);
            $children = implode('.', $nameParts);
            [$prefix, $base] = $this->getResourcePrefixAndBase($children);
        }
        $resourceSlug = \Str::singular(self::formatSlug($preparedName));
        $singularBase = \Str::singular($base);

        $path = "{$preparedName}";
        if (strlen($prefix) > 0) {
            $path = "{$preparedName}/{{$resourceSlug}}/{$prefix}";
        }

        return [$path, $singularBase];
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
