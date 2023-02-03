<?php

namespace GTS\Shared\Domain\Adapter;

class PortRequestBuilder implements RequestBuilderInterface
{
    private ?MethodRequestBuilder $methodRequestBuilder = null;

    public function __construct(
        private string $module,
        private string $port
    ) {}

    public function __call(string $method, array $arguments)
    {
        $this->methodRequestBuilder = new MethodRequestBuilder($this->module, $this->port, $method, $arguments);
        return $this->methodRequestBuilder;
    }

    public function build(): RequestBuilderInterface
    {
        return $this->methodRequestBuilder->build();
    }
}
