<?php

namespace Custom\Framework\Port;

class Request
{
    public function __construct(
        private readonly string $path,
        private readonly array $attributes
    ) {}

    public function __get(string $name)
    {
        return $this->attributes[$name] ?? null;
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
