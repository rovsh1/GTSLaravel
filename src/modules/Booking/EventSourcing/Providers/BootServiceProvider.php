<?php

namespace Module\Booking\EventSourcing\Providers;

use Module\Booking\EventSourcing\Domain\Service\HistoryStorageInterface;
use Module\Booking\EventSourcing\Infrastructure\Service\HistoryStorage;
use Module\Booking\Shared\Providers\BootServiceProvider as SharedBookingServiceProvider;
use Sdk\Module\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(SharedBookingServiceProvider::class);

        $this->app->singleton(HistoryStorageInterface::class, HistoryStorage::class);
        $this->app->register(IntegrationEventServiceProvider::class);
    }
}
