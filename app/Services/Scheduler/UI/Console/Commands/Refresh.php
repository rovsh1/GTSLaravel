<?php

namespace GTS\Services\Scheduler\UI\Console\Commands;

use Ustabor\Services\System\CronService;
use Illuminate\Console\Command;

class Refresh extends Command
{
    protected $signature = 'scheduler:refresh';

    protected $description = '';

    public function handle()
    {
        CronService::updateCrontabFile();
    }
}
