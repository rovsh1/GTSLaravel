<?php

namespace GTS\Shared\UI\Console\Commands;

use GTS\Shared\Domain\Adapter\Manifest\Models\Argument;
use GTS\Shared\Domain\Adapter\Manifest\Models\Manifest;
use GTS\Shared\Domain\Adapter\Manifest\Models\Method;
use GTS\Shared\Domain\Adapter\Manifest\Models\Port;
use Illuminate\Console\Command;

class BuildModulesManifest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'modules:build-manifest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build manifest.json files for all modules';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //@todo получение всех модулей
        $modules = [
            'Hotel',
        ];
        $manifestModules = [];
        foreach ($modules as $module) {
            $modulePorts = module($module)->availablePorts();

            $manifestModulePorts = [];
            foreach ($modulePorts as $port) {
                $portInterface = module($module)->portNamespace($port);
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

                $manifestModulePorts[] = new Port($port, Method::collection(array_values($manifestMethods)));
            }
            $manifest = new Manifest($module, Port::collection($manifestModulePorts));
            $manifestPath = module($module)->manifestPath();
            file_put_contents($manifestPath, $manifest->toJson());
        }

        return Command::SUCCESS;
    }
}
