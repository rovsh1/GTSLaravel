<?php

namespace GTS\Shared\UI\Console\Commands;

use GTS\Shared\Custom\Foundation\Module;
use GTS\Shared\Infrastructure\Service\Manifest\ManifestGeneratorInterface;
use Illuminate\Console\Command;

class BuildModulesManifest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manifest-generator:generate';

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
    public function handle(ManifestGeneratorInterface $generator)
    {
        /** @var Module[] $modules */
        $modules = app('modules')->registeredModules();
        foreach ($modules as $module) {
            $generator->generateModuleManifest($module->name());
        }

        return Command::SUCCESS;
    }
}
