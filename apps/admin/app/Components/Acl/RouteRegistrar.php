<?php

namespace App\Admin\Components\Acl;

use Illuminate\Routing\RouteRegistrar as BaseRegistrar;
use Illuminate\Support\Facades\Route;

class RouteRegistrar
{
    private BaseRegistrar $routeRegistrar;

    private array $actions = [];

    private bool $registered = false;

    public function __construct(
        private readonly Router $router,
        private readonly Resource $resource
    ) {
        $this->routeRegistrar = new BaseRegistrar(Route::getFacadeRoot());
    }

    public function __call(string $name, array $arguments)
    {
        $this->routeRegistrar->$name(...$arguments);
        return $this;
    }

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
        $actionData = new \stdClass();
        $actionData->methods = $methods;
        $actionData->uri = $uri;
        $actionData->action = $action;
        $actionData->name = $name;
        $actionData->permission = $permission;
        $this->actions[] = $actionData;
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

    private function addResourceIndex($prefix, $name, $base, $controller): void
    {
        $this->get($prefix . '/', $controller . '@index', 'read', $name . 'index');
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
        $this->post($path . '/store', $controller . '@create', 'create', $name . 'store');
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

    public function register(): void
    {
        $this->registered = true;

        $this->routeRegistrar
            ->group(function () {
                foreach ($this->actions as $action) {
                    $route = Route::match($action->methods, $action->uri, $action->action)->name($action->name);

                    $this->router->assignRoute($route->getName(), $this->resource->permissionSlug($action->permission));
                }

                $this->actions = [];
            });
    }

    public function __destruct()
    {
        if (!$this->registered) {
            $this->register();
        }
    }

    private static function formatSlug(string $slug): string
    {
        return str_replace('-', '_', $slug);
    }
}
