<?php

namespace GTS\Shared\UI\Console\Commands;

use GTS\Shared\Domain\Adapter\Manifest\Models\Manifest;
use Illuminate\Console\Command;

class GenerateModulesManifestHelpers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'modules:build-manifest-helpers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build manifest helper files for all modules';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //@todo получение всех модулей
        $modules = [
            'Reservation',
            'Hotel',
        ];
        foreach ($modules as $module) {
            if (module($module) === null) {
                $this->warn("Module '{$module}' undefined");
                continue;
            }
            $manifestPath = module($module)->manifestPath();
            if (!file_exists($manifestPath)) {
                $this->warn("Module '{$module}' mainfest.json not found");
                continue;
            }
            $manifestData = file_get_contents($manifestPath);
            $moduleManifest = Manifest::from($manifestData);
            $modulePath = app_path("Shared/Domain/Adapter/Request/{$module}");
            \File::deleteDirectory($modulePath);
            foreach ($moduleManifest->ports as $port) {
                $portPath = $modulePath . DIRECTORY_SEPARATOR . $port->name;
                \File::ensureDirectoryExists($portPath);
                foreach ($port->methods as $method) {
                    $classData = [
                        'module' => $module,
                        'port' => $port->name,
                        'method' => $method->name,
                        'arguments' => $method->arguments
                    ];
                    $classView = view('request', $classData)->render();
                    $className = \Str::ucfirst($method->name) . 'Request.php';
                    \File::put($portPath . DIRECTORY_SEPARATOR . $className, $classView);
                }
            }

        }

        return Command::SUCCESS;
    }
}
