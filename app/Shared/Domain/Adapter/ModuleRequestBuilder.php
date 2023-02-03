<?php

namespace GTS\Shared\Domain\Adapter;

use GTS\Shared\Domain\Adapter\Exception\ModuleManifestNotFound;
use GTS\Shared\Domain\Adapter\Exception\ModuleNotFoundException;

class ModuleRequestBuilder implements RequestBuilderInterface
{
    private ?PortRequestBuilder $portRequestBuilder = null;

    public function __construct(private string $module) {}

    public function __call(string $method, array $arguments)
    {
        $this->validate($method);
        $this->portRequestBuilder = new PortRequestBuilder($this->module, $method);
        return $this->portRequestBuilder;
    }

    public function build(): RequestBuilderInterface
    {
        return $this->portRequestBuilder->build();
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
