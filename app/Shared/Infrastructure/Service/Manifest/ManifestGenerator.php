<?php

namespace GTS\Shared\Infrastructure\Service\Manifest;

use GTS\Shared\Infrastructure\Service\Manifest\Models\Argument;
use GTS\Shared\Infrastructure\Service\Manifest\Models\Manifest;
use GTS\Shared\Infrastructure\Service\Manifest\Models\Method;
use GTS\Shared\Infrastructure\Service\Manifest\Models\Port;

class ManifestGenerator implements ManifestGeneratorInterface
{
    public function generateModuleManifest(string $moduleName): void
    {
        $module = module($moduleName);
        $modulePorts = $module->availablePorts();
        if (count($modulePorts) === 0) {
            return;
        }

        $manifestModulePorts = [];
        foreach ($modulePorts as $port) {
            $portInterface = $module->portNamespace($port->name);
            $portInstance = app($portInterface);
            $class = new \ReflectionClass($portInstance);
            $methods = $class->getMethods();
            $manifestMethods = array_filter(array_map(function (\ReflectionMethod $method) {
                if (!$method->isPublic() || $method->isConstructor()) {
                    return null;
                }

                $manifestArguments = array_map(fn(\ReflectionParameter $argument) => new Argument(
                    $argument->getName(),
                    $argument->getType()->getName(),
                    $argument->getType()->allowsNull(),
                    $argument->isDefaultValueAvailable(),
                    $argument->isDefaultValueAvailable() ? $argument->getDefaultValue() : null
                ), $method->getParameters());

                return new Method($method->getName(), Argument::collection($manifestArguments));
            }, $methods));

            $manifestModulePorts[] = new Port($port->name, Method::collection(array_values($manifestMethods)));
        }
        $manifest = new Manifest($module->name(), Port::collection($manifestModulePorts));
        $manifestPath = $module->manifestPath();
        \File::put($manifestPath, $manifest->toJson());
    }
}
