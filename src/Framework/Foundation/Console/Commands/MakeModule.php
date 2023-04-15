<?php

namespace Custom\Framework\Foundation\Console\Commands;

use Custom\Framework\Console\MakeService\FolderBuilder;
use Illuminate\Console\Command;

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