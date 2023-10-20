<?php

namespace Module\Booking\Providers;

use Module\Booking\Domain\Booking\Service\StatusStorageInterface;
use Module\Booking\Domain\Shared\Adapter\AdministratorAdapterInterface;
use Module\Booking\Domain\Shared\Adapter\ClientAdapterInterface;
use Module\Booking\Domain\Shared\Adapter\CountryAdapterInterface;
use Module\Booking\Domain\Shared\Repository\BookingChangesLogRepositoryInterface;
use Module\Booking\Infrastructure\Service\StatusStorage;
use Module\Booking\Infrastructure\Shared\Adapter\AdministratorAdapter;
use Module\Booking\Infrastructure\Shared\Adapter\ClientAdapter;
use Module\Booking\Infrastructure\Shared\Adapter\CountryAdapter;
use Module\Booking\Infrastructure\Shared\Repository\BookingChangesLogRepository;
use Sdk\Module\Support\ServiceProvider;

class SharedServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(AdministratorAdapterInterface::class, AdministratorAdapter::class);
        $this->app->singleton(ClientAdapterInterface::class, ClientAdapter::class);
        $this->app->singleton(CountryAdapterInterface::class, CountryAdapter::class);

        $this->app->singleton(BookingChangesLogRepositoryInterface::class, BookingChangesLogRepository::class);

        $this->app->singleton(StatusStorageInterface::class, StatusStorage::class);
    }
}
