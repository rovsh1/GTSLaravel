<?php

namespace GTS\Shared\Domain\Adapter;

use GTS\Shared\Domain\Adapter\Exception\InvalidRequestArgumentsException;
use GTS\Shared\Domain\Adapter\Exception\InvalidRequestException;

class Request implements RequestInterface
{
    public function __construct(
        public readonly string $module,
        public readonly string $port,
        public readonly string $method,
        public readonly array  $arguments
    ) {}

    public static function fromArray(array $data): self
    {
        try {
            ['module' => $module, 'port' => $port, 'method' => $method, 'arguments' => $arguments] = $data;
        } catch (\Throwable $e) {
            throw new InvalidRequestException($e->getMessage());
        }
        if (!\Arr::isAssoc($arguments)) {
            throw new InvalidRequestArgumentsException('Arguments should be assoc array');
        }
        return new self($module, $port, $method, $arguments);
    }

    public function module(): string
    {
        return \Str::ucfirst($this->module);
    }

    /**
     * @return string
     */
    public function port(): string
    {
        return \Str::ucfirst($this->port);
    }

    public function method(): string
    {
        return $this->method;
    }

    public function arguments(): array
    {
        return $this->arguments;
    }
}
