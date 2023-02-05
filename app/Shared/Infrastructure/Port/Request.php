<?php

namespace GTS\Shared\Infrastructure\Port;

class Request
{
    private $data = [];

    public function __construct(mixed $data) {}

    public function __get(string $name)
    {
        return $this->data[$name] ?? null;
    }
}
