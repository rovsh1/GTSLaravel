<?php

namespace GTS\Services\PortGateway\UI\Console\Commands;

use GTS\Services\PortGateway\Domain\ValueObject\Mainfest\Manifest;
use GTS\Shared\Custom\Foundation\Module;
use Illuminate\Console\Command;

class GenerateModulesManifestRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'modules:build-manifest-requests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build requests by manifest for all modules';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /** @var Module[] $modules */
        $modules = app('modules')->registeredModules();
        $portGatewayModule = module('PortGateway');
        foreach ($modules as $module) {
            $manifestPath = $module->manifestPath();
            if (!file_exists($manifestPath)) {
                $this->warn("Module '{$module->name()}' mainfest.json not found");
                continue;
            }
            $manifestData = \File::get($manifestPath);
            $moduleManifest = Manifest::from($manifestData);
            $moduleRequestsPath = $portGatewayModule->path("Request/{$module->name()}");
            \File::deleteDirectory($moduleRequestsPath);
            foreach ($moduleManifest->ports as $port) {
                $portPath = $moduleRequestsPath . DIRECTORY_SEPARATOR . $port->name;
                \File::ensureDirectoryExists($portPath);
                foreach ($port->methods as $method) {
                    $classData = [
                        'module' => $module->name(),
                        'port' => $port->name,
                        'method' => $method->name,
                        'arguments' => $method->arguments
                    ];
                    //@todo сейчас не подтягивается из-за того что views лежать внутри других папок
                    $classView = view('helpers.request', $classData)->render();
                    $className = \Str::ucfirst($method->name) . 'Request.php';
                    \File::put($portPath . DIRECTORY_SEPARATOR . $className, $classView);
                }
            }
        }

        return Command::SUCCESS;
    }
}
