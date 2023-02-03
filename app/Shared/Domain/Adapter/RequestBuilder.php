<?php

namespace GTS\Shared\Domain\Adapter;

use GTS\Shared\Domain\Adapter\Exception\ModuleManifestNotFound;
use GTS\Shared\Domain\Adapter\Exception\ModuleNotFoundException;

class RequestBuilder implements RequestBuilderInterface
{
    private ?ModuleRequestBuilder $moduleRequestBuilder = null;

    public function __call(string $method, array $arguments)
    {
        $this->validate($method);
        $this->moduleRequestBuilder = new ModuleRequestBuilder($method);
        return $this->moduleRequestBuilder;
    }

    public function build(): RequestBuilderInterface|RequestInterface
    {
        return $this->moduleRequestBuilder->build();
    }

    private function validate(mixed $data)
    {
        $module = module($data);
        if ($module === null) {
            throw new ModuleNotFoundException("Module '{$data}' not found");
        }
        $manifestPath = $module->manifestPath();
        if (!file_exists($manifestPath)) {
            throw new ModuleManifestNotFound();
        }
    }
}
