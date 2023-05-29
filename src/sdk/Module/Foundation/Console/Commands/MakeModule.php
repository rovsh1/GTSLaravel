<?php

namespace Sdk\Module\Foundation\Console\Commands;

use Illuminate\Console\Command;
use Sdk\Module\Console\MakeService\FolderBuilder;

class MakeModule extends Command
{
    protected $signature = 'make:module {name}';

    protected $description = 'Create module structure';

    public function handle()
    {
        $moduleName = $this->argument('name');
        (new FolderBuilder($moduleName))->build();
    }
}