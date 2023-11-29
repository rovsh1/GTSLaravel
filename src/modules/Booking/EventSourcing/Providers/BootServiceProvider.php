<?php

namespace Module\Booking\EventSourcing\Providers;

use Module\Booking\EventSourcing\Domain\Repository\HistoryRepositoryInterface;
use Module\Booking\EventSourcing\Infrastructure\Repository\HistoryRepository;
use Module\Booking\Shared\Providers\BootServiceProvider as SharedBookingServiceProvider;
use Sdk\Module\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(SharedBookingServiceProvider::class);

        $this->app->singleton(HistoryRepositoryInterface::class, HistoryRepository::class);
        $this->app->register(IntegrationEventServiceProvider::class);
    }
}
