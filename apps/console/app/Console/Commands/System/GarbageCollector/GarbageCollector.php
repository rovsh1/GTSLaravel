<?php

namespace App\Console\Console\Commands\System\GarbageCollector;

use Illuminate\Console\Command;

class GarbageCollector extends Command
{
    public bool $cronable = true;

    protected $signature = 'system:garbage-collector';

    protected $description = 'Сборщик мусора';

    public function handle()
    {
        $this->call('system:clear-temp-files');
    }
}
