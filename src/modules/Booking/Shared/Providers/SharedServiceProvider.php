<?php

namespace Module\Booking\Shared\Providers;

use Module\Booking\Shared\Domain\Booking\Repository\AirportBookingGuestRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Service\BookingStatusStorageInterface;
use Module\Booking\Shared\Domain\Order\Service\OrderStatusStorageInterface;
use Module\Booking\Shared\Domain\Shared\Adapter\AdministratorAdapterInterface;
use Module\Booking\Shared\Domain\Shared\Adapter\ClientAdapterInterface;
use Module\Booking\Shared\Domain\Shared\Repository\BookingChangesLogRepositoryInterface;
use Module\Booking\Shared\Infrastructure\Adapter\AdministratorAdapter;
use Module\Booking\Shared\Infrastructure\Adapter\ClientAdapter;
use Module\Booking\Shared\Infrastructure\Repository\BookingChangesLogRepository;
use Module\Booking\Shared\Infrastructure\Service\BookingStatusStorage;
use Module\Booking\Shared\Infrastructure\Service\OrderStatusStorage;
use Module\Booking\Shared\Infrastructure\ServiceBooking\Repository\AirportAirportBookingGuestRepository;
use Sdk\Module\Support\ServiceProvider;

class SharedServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(BookingStatusStorageInterface::class, BookingStatusStorage::class);
        $this->app->singleton(OrderStatusStorageInterface::class, OrderStatusStorage::class);
        $this->app->singleton(AdministratorAdapterInterface::class, AdministratorAdapter::class);
        $this->app->singleton(ClientAdapterInterface::class, ClientAdapter::class);

        $this->app->singleton(BookingChangesLogRepositoryInterface::class, BookingChangesLogRepository::class);

        $this->app->singleton(
            AirportBookingGuestRepositoryInterface::class,
            AirportAirportBookingGuestRepository::class
        );
    }
}
