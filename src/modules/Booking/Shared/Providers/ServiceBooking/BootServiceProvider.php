<?php

namespace Module\Booking\Shared\Providers\ServiceBooking;

use Module\Booking\Infrastructure\ServiceBooking\Repository\Details as Infrastructure;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\CarRentWithDriverRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\CIPRoomInAirportRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\DayCarTripRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\HotelBookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\IntercityTransferRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\OtherServiceRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\TransferFromAirportRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\TransferFromRailwayRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\TransferToAirportRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\TransferToRailwayRepositoryInterface;
use Module\Booking\Shared\Infrastructure\ServiceBooking\Adapter\SupplierAdapter;
use Module\Booking\Shared\Infrastructure\ServiceBooking\Repository\BookingRepository;
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
            \Module\Booking\Shared\Infrastructure\ServiceBooking\Repository\Details\CIPRoomInAirportRepository::class
        );
        $this->app->singleton(
            TransferToAirportRepositoryInterface::class,
            \Module\Booking\Shared\Infrastructure\ServiceBooking\Repository\Details\TransferToAirportRepository::class
        );
        $this->app->singleton(
            TransferFromAirportRepositoryInterface::class,
            \Module\Booking\Shared\Infrastructure\ServiceBooking\Repository\Details\TransferFromAirportRepository::class
        );
        $this->app->singleton(
            TransferToRailwayRepositoryInterface::class,
            \Module\Booking\Shared\Infrastructure\ServiceBooking\Repository\Details\TransferToRailwayRepository::class
        );
        $this->app->singleton(
            TransferFromRailwayRepositoryInterface::class,
            \Module\Booking\Shared\Infrastructure\ServiceBooking\Repository\Details\TransferFromRailwayRepository::class
        );
        $this->app->singleton(
            HotelBookingRepositoryInterface::class,
            \Module\Booking\Shared\Infrastructure\ServiceBooking\Repository\Details\HotelBookingRepository::class
        );
        $this->app->singleton(
            CarRentWithDriverRepositoryInterface::class,
            \Module\Booking\Shared\Infrastructure\ServiceBooking\Repository\Details\CarRentWithDriverRepository::class
        );
        $this->app->singleton(
            IntercityTransferRepositoryInterface::class,
            \Module\Booking\Shared\Infrastructure\ServiceBooking\Repository\Details\IntercityTransferRepository::class
        );
        $this->app->singleton(
            DayCarTripRepositoryInterface::class,
            \Module\Booking\Shared\Infrastructure\ServiceBooking\Repository\Details\DayCarTripRepository::class
        );
        $this->app->singleton(
            OtherServiceRepositoryInterface::class,
            \Module\Booking\Shared\Infrastructure\ServiceBooking\Repository\Details\OtherServiceRepository::class
        );

        $this->app->singleton(
            SupplierAdapterInterface::class,
            SupplierAdapter::class
        );
    }
}
