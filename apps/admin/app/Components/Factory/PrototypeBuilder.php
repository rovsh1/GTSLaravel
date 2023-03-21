<?php

namespace App\Admin\Components\Factory;

use App\Admin\Components\Factory\Support\NotImplementedController;

/**
 * @method self key(string $key)
 * @method self category(string $category)
 * @method self group(string $group)
 * @method self alias(string $alias)
 * @method self route(string $route)
 * @method self model(string $model)
 * @method self repository(string $group)
 * @method self titles(array $titles)
 * @method self views(array $views)
 * @method self permissions(array $permissions)
 * @method self priority(int $priority)
 *
 * @see \App\Admin\Components\Acl\AccessControl
 */
class PrototypeBuilder
{
    private array $config = [
        'permissions' => ['read', 'update', 'create', 'delete'],
        'controller' => NotImplementedController::class
    ];

    public function __call(string $name, array $arguments)
    {
        if (count($arguments) !== 1) {
            throw new \Exception('Method [' . $name . '] undefined or bad call');
        }
        return $this->set($name, $arguments[0]);
    }

    public function controller(string $controller, array $routeOptions = null): static
    {
        $this->set('controller', $controller);
        if ($routeOptions) {
            $this->set('routeOptions', $routeOptions);
        }
        return $this;
    }

    public function build(): Prototype
    {
        return new Prototype($this->config);
    }

    public function readOnly(): static
    {
        return $this->permissions(['read']);
    }

    private function set(string $key, $value): static
    {
        $this->config[$key] = $value;
        return $this;
    }
}
