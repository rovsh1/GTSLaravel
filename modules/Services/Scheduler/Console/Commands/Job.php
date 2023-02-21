<?php

namespace Module\Services\Scheduler\Console\Commands;

use Module\Services\Scheduler\UI\Console\Commands\Cron;
use Module\Services\Scheduler\UI\Console\Commands\CronService;
use Illuminate\Console\Command;

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
