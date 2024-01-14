<?php

namespace App\Shared\Support\Module\Monolith;

use App\Shared\Contracts\Module\ModuleAdapterInterface;
use Sdk\Module\Contracts\ModuleInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\Contracts\Context\ContextInterface;
use Sdk\Shared\Contracts\Event\IntegrationEventSubscriberInterface;
use Sdk\Shared\Event\IntegrationEventMessage;

class ModuleAdapter implements ModuleAdapterInterface
{
    public function __construct(
        private readonly string $name,
        private readonly ModuleInterface $module,
    ) {}

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
        if (!is_subclass_of($method, UseCaseInterface::class)) {
            throw new \Exception('Only use case allowed');
        }

        /**
         * Передаем контекст приложения в контекст модуля
         */
        $this->module->withContext(app(ContextInterface::class)->toArray());

        return $this->module->make($method)->execute(...$arguments);
    }

    public function hasSubclass(string $abstract): bool
    {
        return $this->module->hasSubclass($abstract);
    }

    public function make(string $abstract)
    {
        return $this->module->make($abstract);
    }

    public function dispatchEvent(IntegrationEventMessage $message): void
    {
        $this->module->get(IntegrationEventSubscriberInterface::class)->handle($message);
    }
}
