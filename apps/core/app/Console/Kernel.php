<?php

namespace App\Core\Console;

use Custom\Framework\Services\NamespaceReader;
use Illuminate\Console\Application as Artisan;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Module\Integration\Traveline\Infrastructure\Jobs\Legacy\SendTravelineNotifications;
use Module\Integration\Traveline\Infrastructure\Jobs\Legacy\SyncTravelineReservations;

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
        $schedule->job(SyncTravelineReservations::class, null, 'sync');
        $schedule->job(SendTravelineNotifications::class, null, 'sync');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $loadPaths = [];

        //$loadPaths[] = __DIR__ . '/Commands';
        //$loadPaths[] = admin_path('app/Console/Commands');
        $this->loadNamespacePath('App\\Admin\\Console\\Commands', admin_path('app/Console/Commands'));
//        $this->loadNamespacePath('App\\Site\\Console\\Commands', site_path('app/Console/Commands'));
//        $this->loadNamespacePath('App\\Api\\Console\\Commands', api_path('app/Console/Commands'));

        foreach (app('modules')->registeredModules() as $module) {
            $commandsPath = $module->path('Console/Commands');
            if (is_dir($commandsPath)) {
                $this->loadNamespacePath($module->namespace('Console\\Commands'), $commandsPath);
                //$loadPaths[] = $commandsPath;
            }
        }

        //$this->load($loadPaths);

        require __DIR__ . '/routes.php';
    }

    protected function loadNamespacePath($namespace, $path)
    {
        foreach ((new NamespaceReader($namespace, $path))->read() as $command) {
            if (is_subclass_of($command, Command::class) &&
                !(new \ReflectionClass($command))->isAbstract()) {
                Artisan::starting(function ($artisan) use ($command) {
                    $artisan->resolve($command);
                });
            }
        }
    }
}
