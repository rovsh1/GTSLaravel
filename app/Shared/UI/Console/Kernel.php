<?php

namespace GTS\Shared\UI\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $loadPaths = [__DIR__ . '/Commands'];
        foreach (app('modules')->paths() as $modulePath) {
            $commandsPath = $modulePath . '/UI/Console/Commands';
            if (is_dir($commandsPath)) {
                $loadPaths[] = $commandsPath;
            }
        }
        $this->load($loadPaths);

        require __DIR__ . '/routes.php';
    }
}
