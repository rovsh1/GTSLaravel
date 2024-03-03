<?php

namespace App\Shared\Console;

use Illuminate\Console\Application as Artisan;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Module\Hotel\Moderation\Infrastructure\Command\RefreshRatingsCommand;
use Sdk\Module\Services\NamespaceReader;
use Support\LocaleTranslator\LocaleSyncCommand;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        LocaleSyncCommand::class,
        RefreshRatingsCommand::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        if (app()->environment('production')) {
            $this->scheduleProduction($schedule);
        }
    }

    private function scheduleProduction(Schedule $schedule): void
    {
        $schedule->command('backup:run --only-db')
            ->dailyAt('03:00');

        $schedule->command('backup:run')
            ->weeklyOn(0, '04:00');
    }

    protected function commands()
    {
        //$loadPaths[] = admin_path('app/Console/Commands');
        $this->loadNamespacePath('App\\Shared\\Console\\Commands', __DIR__ . '/Commands');
//        $this->loadNamespacePath('App\\Site\\Console\\Commands', site_path('app/Console/Commands'));
//        $this->loadNamespacePath('App\\Api\\Console\\Commands', api_path('app/Console/Commands'));

        //$this->load(__DIR__ . '/Commands');

        require __DIR__ . '/routes.php';
    }

    protected function loadNamespacePath($namespace, $path): void
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
