<?php

namespace GTS\Shared\Domain\Adapter\Manifest;

use GTS\Shared\Domain\Adapter\Manifest\Models\Argument;
use GTS\Shared\Domain\Adapter\Manifest\Models\Manifest;
use GTS\Shared\Domain\Adapter\Manifest\Models\Method;
use GTS\Shared\Domain\Adapter\Manifest\Models\Port;

class Analyser
{
    private Manifest $manifest;

    public function __construct(
        public readonly string $manifestPath
    )
    {
        $manifestData = json_decode(file_get_contents($manifestPath), true);
        $this->manifest = Manifest::from($manifestData);
    }

    public function getMethodArgumentsRules(string $portName, string $methodName): array
    {
        /** @var Port $port */
        $port = $this->manifest->ports->first(fn(Port $port) => $port->name === $portName);
        $method = $port->methods->first(fn(Method $method) => $method->name === $methodName);
        $rules = array_map(fn(Argument $argument) => $argument->validationRules(), $method->arguments->items());
        return array_merge(...$rules);
    }
}
