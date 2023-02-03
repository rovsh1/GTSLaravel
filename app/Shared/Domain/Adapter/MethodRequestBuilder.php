<?php

namespace GTS\Shared\Domain\Adapter;

class MethodRequestBuilder implements RequestBuilderInterface
{
    public function __construct(
        private string $module,
        private string $port,
        private string $method,
        private array  $arguments,
    ) {}

    public function build(): RequestBuilderInterface|RequestInterface
    {
        return new Request($this->module, $this->port, $this->method, $this->arguments);
    }
}
