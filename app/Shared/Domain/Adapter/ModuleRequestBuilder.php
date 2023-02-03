<?php

namespace GTS\Shared\Domain\Adapter;

class ModuleRequestBuilder implements RequestBuilderInterface
{
    private ?PortRequestBuilder $portRequestBuilder = null;

    public function __construct(private string $module) {}

    public function __call(string $method, array $arguments)
    {
        $this->portRequestBuilder = new PortRequestBuilder($this->module, $method);
        return $this->portRequestBuilder;
    }

    public function build(): RequestBuilderInterface
    {
        return $this->portRequestBuilder->build();
    }
}
