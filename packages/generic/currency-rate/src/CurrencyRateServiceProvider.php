<?php

namespace Pkg\CurrencyRate;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Pkg\CurrencyRate\Console\Commands\UpdateRates;
use Pkg\CurrencyRate\Contracts\ApiRepositoryInterface;
use Pkg\CurrencyRate\Contracts\CacheRepositoryInterface;
use Pkg\CurrencyRate\Contracts\DatabaseRepositoryInterface;
use Pkg\CurrencyRate\Repository\ApiRepository;
use Pkg\CurrencyRate\Repository\CacheRepository;
use Pkg\CurrencyRate\Repository\DatabaseRepository;

class CurrencyRateServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerServices();
        $this->registerMigrations();
        $this->registerCommands();
        $this->schedule();
    }

    protected function registerServices(): void
    {
        $this->app->singleton(DatabaseRepositoryInterface::class, DatabaseRepository::class);
        $this->app->singleton(CacheRepositoryInterface::class, CacheRepository::class);
        $this->app->singleton(ApiRepositoryInterface::class, ApiRepository::class);
    }

    protected function registerMigrations(): void
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }

    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                UpdateRates::class
            ]);
        }
    }

    protected function schedule(): void
    {
        $this->app->resolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command(UpdateRates::class)->dailyAt('00:10');
        });
    }
}
