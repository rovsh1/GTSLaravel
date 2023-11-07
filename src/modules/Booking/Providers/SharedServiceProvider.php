<?php

namespace Module\Booking\Providers;

use Module\Booking\Domain\Booking\Repository\AirportBookingGuestRepositoryInterface;
use Module\Booking\Domain\Booking\Service\BookingStatusStorageInterface;
use Module\Booking\Domain\Order\Service\OrderStatusStorageInterface;
use Module\Booking\Domain\Shared\Adapter\AdministratorAdapterInterface;
use Module\Booking\Domain\Shared\Adapter\ClientAdapterInterface;
use Module\Booking\Domain\Shared\Repository\BookingChangesLogRepositoryInterface;
use Module\Booking\Infrastructure\Adapter\AdministratorAdapter;
use Module\Booking\Infrastructure\Adapter\ClientAdapter;
use Module\Booking\Infrastructure\Repository\BookingChangesLogRepository;
use Module\Booking\Infrastructure\Service\BookingStatusStorage;
use Module\Booking\Infrastructure\Service\OrderStatusStorage;
use Module\Booking\Infrastructure\ServiceBooking\Repository\AirportAirportBookingGuestRepository;
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
