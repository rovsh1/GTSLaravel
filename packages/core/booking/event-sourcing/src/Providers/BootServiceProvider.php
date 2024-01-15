<?php

namespace Pkg\Booking\EventSourcing\Providers;

use Module\Booking\Shared\Providers\BootServiceProvider as SharedBookingServiceProvider;
use Pkg\Booking\EventSourcing\Domain\Service\HistoryStorageInterface;
use Pkg\Booking\EventSourcing\Infrastructure\Service\HistoryStorage;
use Sdk\Module\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerMigrations();

        $this->app->register(SharedBookingServiceProvider::class);

        $this->app->singleton(HistoryStorageInterface::class, HistoryStorage::class);
        $this->app->register(IntegrationEventServiceProvider::class);
    }

    protected function registerMigrations(): void
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        }
    }
}
