<?php

namespace Sdk\Module\PortGateway\Client;

class Request
{
    public function __construct(
        private readonly string $module,
        private readonly string $path,
        private readonly array $attributes = [],
    ) {}

    public static function fromRoute(string $route, array $attributes = []): static
    {
        $segments = explode('/', $route);
        $moduleName = array_shift($segments);
        return new static($moduleName, implode('/', $segments), $attributes);
    }

    public function module(): string
    {
        return $this->module;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function attributes(): array
    {
        return $this->attributes;
    }
}
