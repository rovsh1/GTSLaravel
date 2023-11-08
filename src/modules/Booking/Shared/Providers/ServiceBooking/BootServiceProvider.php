<?php

namespace Module\Booking\Shared\Providers\ServiceBooking;

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
use Module\Booking\Shared\Infrastructure\ServiceBooking\Repository\Details\CarRentWithDriverRepository;
use Module\Booking\Shared\Infrastructure\ServiceBooking\Repository\Details\CIPRoomInAirportRepository;
use Module\Booking\Shared\Infrastructure\ServiceBooking\Repository\Details\DayCarTripRepository;
use Module\Booking\Shared\Infrastructure\ServiceBooking\Repository\Details\HotelBookingRepository;
use Module\Booking\Shared\Infrastructure\ServiceBooking\Repository\Details\IntercityTransferRepository;
use Module\Booking\Shared\Infrastructure\ServiceBooking\Repository\Details\OtherServiceRepository;
use Module\Booking\Shared\Infrastructure\ServiceBooking\Repository\Details\TransferFromAirportRepository;
use Module\Booking\Shared\Infrastructure\ServiceBooking\Repository\Details\TransferFromRailwayRepository;
use Module\Booking\Shared\Infrastructure\ServiceBooking\Repository\Details\TransferToAirportRepository;
use Module\Booking\Shared\Infrastructure\ServiceBooking\Repository\Details\TransferToRailwayRepository;
use Sdk\Module\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
    }

    public function boot(): void
    {
        $this->app->singleton(BookingRepositoryInterface::class, BookingRepository::class);
        $this->app->singleton(CIPRoomInAirportRepositoryInterface::class, CIPRoomInAirportRepository::class);
        $this->app->singleton(TransferToAirportRepositoryInterface::class, TransferToAirportRepository::class);
        $this->app->singleton(TransferFromAirportRepositoryInterface::class, TransferFromAirportRepository::class);
        $this->app->singleton(TransferToRailwayRepositoryInterface::class, TransferToRailwayRepository::class);
        $this->app->singleton(TransferFromRailwayRepositoryInterface::class, TransferFromRailwayRepository::class);
        $this->app->singleton(HotelBookingRepositoryInterface::class, HotelBookingRepository::class);
        $this->app->singleton(CarRentWithDriverRepositoryInterface::class, CarRentWithDriverRepository::class);
        $this->app->singleton(IntercityTransferRepositoryInterface::class, IntercityTransferRepository::class);
        $this->app->singleton(DayCarTripRepositoryInterface::class, DayCarTripRepository::class);
        $this->app->singleton(OtherServiceRepositoryInterface::class, OtherServiceRepository::class);
        $this->app->singleton(SupplierAdapterInterface::class, SupplierAdapter::class);
    }
}
