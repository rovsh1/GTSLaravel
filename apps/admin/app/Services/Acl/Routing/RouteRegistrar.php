<?php

namespace App\Admin\Services\Acl\Routing;

use App\Admin\Services\Acl\Resource;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\RouteRegistrar as BaseRegistrar;

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

    public function get(string $uri, string $action, string $permission): static
    {
        return $this->addAction(['GET'], $uri, $action, $permission);
    }

    public function post(string $uri, string $action, string $permission): static
    {
        return $this->addAction(['POST'], $uri, $action, $permission);
    }

    public function put(string $uri, string $action, string $permission): static
    {
        return $this->addAction(['PUT'], $uri, $action, $permission);
    }

    public function patch(string $uri, string $action, string $permission): static
    {
        return $this->addAction(['PATCH'], $uri, $action, $permission);
    }

    public function delete(string $uri, string $action, string $permission): static
    {
        return $this->addAction(['DELETE'], $uri, $action, $permission);
    }

    public function options(string $uri, string $action, string $permission): static
    {
        return $this->addAction(['OPTIONS'], $uri, $action, $permission);
    }

    public function any(string $uri, string $action, string $permission): static
    {
        return $this->addAction(\Illuminate\Routing\Router::$verbs, $uri, $action, $permission);
    }

    public function match(array $methods, string $uri, string $action, string $permission): static
    {
        return $this->addAction($methods, $uri, $action, $permission);
    }

    public function addAction(array $methods, string $uri, string $action, string $permission): static
    {
        $actionData = new \stdClass();
        $actionData->methods = $methods;
        $actionData->uri = $uri;
        $actionData->action = $action;
        $actionData->permission = $permission;
        $this->actions[] = $actionData;
        return $this;
    }

    public function resource(string $controller, array $options = []): static
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

        foreach ($methods as $method) {
            $this->{'addResource' . ucfirst($method)}();
        }

        return $this->controller($controller);
    }

    private function addResourceIndex(): void
    {
        $this->get('/', 'index', 'read');
    }

    private function addResourceShow(): void
    {
        $this->get('/{' . $this->resource->key . '}', 'show', 'read');
    }

    private function addResourceCreate(): void
    {
        $this->get('/create', 'create', 'create');
    }

    private function addResourceStore(): void
    {
        $this->post('/', 'store', 'create');
    }

    private function addResourceEdit(): void
    {
        $this->get('/{' . $this->resource->key . '}/edit', 'edit', 'update');
    }

    private function addResourceUpdate(): void
    {
        $this->match(['PUT', 'PATCH'], '/{' . $this->resource->key . '}', 'update', 'update');
    }

    private function addResourceDestroy(): void
    {
        $this->delete('/{' . $this->resource->key . '}', 'destroy', 'delete');
    }

    public function register(): void
    {
        $this->registered = true;

        $this->routeRegistrar
            ->prefix($this->resource->routePrefix())
            ->name($this->resource->routeName())
            ->group(function () {
                foreach ($this->actions as $action) {
                    $route = Route::match($action->methods, $action->uri, $action->action)->name($action->action);

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
}
