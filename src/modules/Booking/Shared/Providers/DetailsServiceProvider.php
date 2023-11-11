<?php

namespace Module\Booking\Shared\Providers;

use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\CarRentWithDriverRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\CIPMeetingInAirportRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\CIPSendoffInAirportRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\DayCarTripRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\HotelBookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\IntercityTransferRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\OtherServiceRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\TransferFromAirportRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\TransferFromRailwayRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\TransferToAirportRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\Details\TransferToRailwayRepositoryInterface;
use Module\Booking\Shared\Infrastructure\Adapter\SupplierAdapter;
use Module\Booking\Shared\Infrastructure\Repository\Details\CarRentWithDriverRepository;
use Module\Booking\Shared\Infrastructure\Repository\Details\CIPMeetingInAirportRepository;
use Module\Booking\Shared\Infrastructure\Repository\Details\CIPSendoffInAirportRepository;
use Module\Booking\Shared\Infrastructure\Repository\Details\DayCarTripRepository;
use Module\Booking\Shared\Infrastructure\Repository\Details\HotelBookingRepository;
use Module\Booking\Shared\Infrastructure\Repository\Details\IntercityTransferRepository;
use Module\Booking\Shared\Infrastructure\Repository\Details\OtherServiceRepository;
use Module\Booking\Shared\Infrastructure\Repository\Details\TransferFromAirportRepository;
use Module\Booking\Shared\Infrastructure\Repository\Details\TransferFromRailwayRepository;
use Module\Booking\Shared\Infrastructure\Repository\Details\TransferToAirportRepository;
use Module\Booking\Shared\Infrastructure\Repository\Details\TransferToRailwayRepository;
use Sdk\Module\Support\ServiceProvider;

class DetailsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(CIPMeetingInAirportRepositoryInterface::class, CIPMeetingInAirportRepository::class);
        $this->app->singleton(CIPSendoffInAirportRepositoryInterface::class, CIPSendoffInAirportRepository::class);
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
