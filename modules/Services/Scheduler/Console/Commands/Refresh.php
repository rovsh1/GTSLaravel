<?php

namespace Module\Services\Scheduler\Console\Commands;

use Illuminate\Console\Command;
use Ustabor\Services\System\CronService;

class Refresh extends Command
{
    protected $signature = 'scheduler:refresh';

    protected $description = '';

    public function handle()
    {
        CronService::updateCrontabFile();
    }
}
