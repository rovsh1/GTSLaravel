<?php

namespace App\Shared\Contracts\Module;

use Sdk\Module\Contracts\Event\IntegrationEventMessage;

interface ModuleAdapterInterface
{
    public function name(): string;

    public function is(string|ModuleAdapterInterface $name): bool;

    public function isBooted(): bool;

    public function boot(): void;

    public function call(string $method, array $arguments = []): mixed;

    public function dispatchEvent(IntegrationEventMessage $message): void;
}
