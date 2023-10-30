<?php

namespace App\Shared\Support\Module\Monolith;

use App\Shared\Contracts\Module\ModuleAdapterInterface;
use Sdk\Module\Contracts\Api\ApiInterface;
use Sdk\Module\Contracts\Event\IntegrationEventInterface;
use Sdk\Module\Contracts\Event\IntegrationEventSubscriberInterface;
use Sdk\Module\Contracts\ModuleInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class ModuleAdapter implements ModuleAdapterInterface
{
    public function __construct(
        private readonly string $name,
        private readonly ModuleInterface $module,
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function is(string|ModuleAdapterInterface $name): bool
    {
        if ($name instanceof ModuleAdapter) {
            return $this->is($name->name());
        }

        $name = strtolower($name);

        return strtolower($this->name) === $name || $this->module->config('alias') === $name;
    }

    public function isBooted(): bool
    {
        return $this->module->isBooted();
    }

    public function boot(): void
    {
        $this->module->boot();
    }

    public function call(string $method, array $arguments = []): mixed
    {
        if (is_subclass_of($method, ApiInterface::class)) {
            //@deprecated
            return $this->module->make($method)->execute(...$arguments);
        } elseif (is_subclass_of($method, UseCaseInterface::class)) {
            return $this->module->make($method)->execute(...$arguments);
        } else {
            throw new \Exception('Only use case allowed');
        }
    }

    public function hasSubclass(string $abstract): bool
    {
        return $this->module->hasSubclass($abstract);
    }

    public function make(string $abstract)
    {
        return $this->module->make($abstract);
    }

    public function dispatchEvent(IntegrationEventInterface $event): void
    {
        $this->module->get(IntegrationEventSubscriberInterface::class)->handle($event);
    }
}
