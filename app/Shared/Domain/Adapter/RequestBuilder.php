<?php

namespace GTS\Shared\Domain\Adapter;

class RequestBuilder implements RequestBuilderInterface
{
    private ?ModuleRequestBuilder $moduleRequestBuilder = null;

    public function __call(string $method, array $arguments)
    {
        $this->moduleRequestBuilder = new ModuleRequestBuilder($method);
        return $this->moduleRequestBuilder;
    }

    public function build(): RequestBuilderInterface|RequestInterface
    {
        return $this->moduleRequestBuilder->build();
    }
}
