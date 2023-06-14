<?php

namespace Sdk\Module\Foundation\Support;

use Sdk\Module\Contracts\Api\ApiInterface;
use Sdk\Module\Contracts\Bus\IntegrationEventBusInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Module\Foundation\ModulesManager;

class SharedContainer
{
    private array $instances = [];

    private array $bindings = [];

    public function instance(string $abstract, $instance)
    {
        return $this->instances[$abstract] = $instance;
    }

    public function bind(string $abstract, \Closure $bind): void
    {
        $this->bindings[$abstract] = $bind;
    }

    public function has(string $abstract): bool
    {
        return isset($this->instances[$abstract]) || isset($this->bindings[$abstract]);
    }

    public function get(string $abstract)
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        } elseif (isset($this->bindings[$abstract])) {
            $this->instances[$abstract] = $this->bindings[$abstract]();
            unset($this->bindings[$abstract]);

            return $this->instances[$abstract];
        } else {
            return null;
        }
    }
}
