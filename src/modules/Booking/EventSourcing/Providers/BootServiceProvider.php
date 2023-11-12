<?php

namespace Module\Booking\EventSourcing\Providers;

use Module\Booking\EventSourcing\Domain\Repository\BookingLogRepositoryInterface;
use Module\Booking\EventSourcing\Infrastructure\Repository\BookingLogRepository;
use Module\Booking\Shared\Providers\BootServiceProvider as SharedBookingServiceProvider;
use Sdk\Module\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(SharedBookingServiceProvider::class);

        $this->app->singleton(BookingLogRepositoryInterface::class, BookingLogRepository::class);
        $this->app->register(IntegrationEventServiceProvider::class);
    }
}
