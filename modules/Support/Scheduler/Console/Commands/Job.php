<?php

namespace Module\Support\Scheduler\Console\Commands;

use Illuminate\Console\Command;
use Module\Services\Scheduler\UI\Console\Commands\Cron;
use Module\Services\Scheduler\UI\Console\Commands\CronService;

class Job extends Command
{
    protected $signature = 'scheduler:job
		{id}';

    protected $description = '';

    public function handle()
    {
        $job = Cron::find($this->argument('id'));
        if (!$job)
            return $this->error('Cron job not found');
        //else if ($job->enabled)
        CronService::run($job);
    }
}
