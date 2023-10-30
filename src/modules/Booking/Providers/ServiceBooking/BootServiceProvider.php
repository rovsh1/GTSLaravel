<?php

namespace Module\Booking\Providers\ServiceBooking;

use Module\Booking\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\Details\CarRentWithDriverRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\Details\CIPRoomInAirportRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\Details\DayCarTripRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\Details\HotelBookingRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\Details\IntercityTransferRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\Details\OtherServiceRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\Details\TransferFromAirportRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\Details\TransferFromRailwayRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\Details\TransferToAirportRepositoryInterface;
use Module\Booking\Domain\Booking\Repository\Details\TransferToRailwayRepositoryInterface;
use Module\Booking\Infrastructure\ServiceBooking\Adapter\SupplierAdapter;
use Module\Booking\Infrastructure\ServiceBooking\Repository\BookingRepository;
use Module\Booking\Infrastructure\ServiceBooking\Repository\Details as Infrastructure;
use Sdk\Module\Support\ServiceProvider;

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
            TransferToRailwayRepositoryInterface::class,
            Infrastructure\TransferToRailwayRepository::class
        );
        $this->app->singleton(
            TransferFromRailwayRepositoryInterface::class,
            Infrastructure\TransferFromRailwayRepository::class
        );
        $this->app->singleton(
            HotelBookingRepositoryInterface::class,
            Infrastructure\HotelBookingRepository::class
        );
        $this->app->singleton(
            CarRentWithDriverRepositoryInterface::class,
            Infrastructure\CarRentWithDriverRepository::class
        );
        $this->app->singleton(
            IntercityTransferRepositoryInterface::class,
            Infrastructure\IntercityTransferRepository::class
        );
        $this->app->singleton(
            DayCarTripRepositoryInterface::class,
            Infrastructure\DayCarTripRepository::class
        );
        $this->app->singleton(
            OtherServiceRepositoryInterface::class,
            Infrastructure\OtherServiceRepository::class
        );

        $this->app->singleton(
            SupplierAdapterInterface::class,
            SupplierAdapter::class
        );
    }
}
