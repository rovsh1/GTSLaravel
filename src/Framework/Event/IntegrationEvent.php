<?php

namespace Custom\Framework\Event;

use Custom\Framework\Contracts\Event\IntegrationEventInterface;

class IntegrationEvent implements IntegrationEventInterface
{
    public function __construct(
        private readonly string $module,
        private readonly string $event,
        private readonly array $payload,
    ) {}

    public function __get(string $name)
    {
        return $this->payload[$name] ?? null;
    }

    public function module(): string
    {
        return $this->module;
    }

    public function event(): string
    {
        return $this->module;
    }

    public function key(): string
    {
        return $this->module . '\\' . $this->event;
    }

    public function payload(): array
    {
        return $this->payload;
    }
}
