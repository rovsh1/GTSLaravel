<?php

namespace App\Admin\Components\Acl;

use Illuminate\Support\Facades\Route;

class RoutesCollection
{
    private array $actions = [];

    public function __construct(
        private readonly Router $router
    ) {}

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

    public function register(): void
    {
        foreach ($this->actions as $action) {
            $route = Route::match($action->methods, $action->uri, $action->action)->name($action->name);

            $this->router->assignRoute($route->getName(), $action->permission);
        }

        $this->actions = [];
    }
}
