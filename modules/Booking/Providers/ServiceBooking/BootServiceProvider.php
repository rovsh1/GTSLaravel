<?php

namespace Module\Booking\Providers\ServiceBooking;

use Module\Booking\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\Details\CIPRoomInAirportRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\Details\TransferFromAirportRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\Details\TransferToAirportRepositoryInterface;
use Module\Booking\Infrastructure\ServiceBooking\Adapter\SupplierAdapter;
use Module\Booking\Infrastructure\ServiceBooking\Repository\BookingRepository;
use Module\Booking\Infrastructure\ServiceBooking\Repository\Details as Infrastructure;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(EventServiceProvider::class);

        $this->app->singleton(
            BookingRepositoryInterface::class,
            BookingRepository::class
        );

        $this->app->singleton(
            CIPRoomInAirportRepositoryInterface::class,
            Infrastructure\CIPRoomInAirportRepository::class
        );
        $this->app->singleton(
            TransferToAirportRepositoryInterface::class,
            Infrastructure\TransferToAirportRepository::class
        );
        $this->app->singleton(
            TransferFromAirportRepositoryInterface::class,
            Infrastructure\TransferFromAirportRepository::class
        );

        $this->app->singleton(
            SupplierAdapterInterface::class,
            SupplierAdapter::class
        );
    }
}
