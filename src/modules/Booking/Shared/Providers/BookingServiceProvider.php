<?php

namespace Module\Booking\Shared\Providers;

use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Service\BookingStatusStorageInterface;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Module\Booking\Shared\Infrastructure\Repository\BookingRepository;
use Module\Booking\Shared\Infrastructure\Service\BookingStatusStorage;
use Module\Booking\Shared\Infrastructure\Service\BookingUnitOfWork;
use Sdk\Module\Support\ServiceProvider;

class BookingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(BookingRepositoryInterface::class, BookingRepository::class);
        $this->app->singleton(BookingStatusStorageInterface::class, BookingStatusStorage::class);
        $this->app->singleton(BookingUnitOfWorkInterface::class, BookingUnitOfWork::class);
    }
}
