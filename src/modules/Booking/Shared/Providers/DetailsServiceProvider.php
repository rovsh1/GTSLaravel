<?php

namespace Module\Booking\Shared\Providers;

use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Factory\Details\CarRentWithDriverFactoryInterface;
use Module\Booking\Shared\Domain\Booking\Factory\Details\CIPMeetingInAirportFactoryInterface;
use Module\Booking\Shared\Domain\Booking\Factory\Details\CIPSendoffInAirportFactoryInterface;
use Module\Booking\Shared\Domain\Booking\Factory\Details\DayCarTripFactoryInterface;
use Module\Booking\Shared\Domain\Booking\Factory\Details\HotelBookingFactoryInterface;
use Module\Booking\Shared\Domain\Booking\Factory\Details\IntercityTransferFactoryInterface;
use Module\Booking\Shared\Domain\Booking\Factory\Details\OtherServiceFactoryInterface;
use Module\Booking\Shared\Domain\Booking\Factory\Details\TransferFromAirportFactoryInterface;
use Module\Booking\Shared\Domain\Booking\Factory\Details\TransferFromRailwayFactoryInterface;
use Module\Booking\Shared\Domain\Booking\Factory\Details\TransferToAirportFactoryInterface;
use Module\Booking\Shared\Domain\Booking\Factory\Details\TransferToRailwayFactoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Module\Booking\Shared\Infrastructure\Adapter\SupplierAdapter;
use Module\Booking\Shared\Infrastructure\Factory\Details\CarRentWithDriverFactory;
use Module\Booking\Shared\Infrastructure\Factory\Details\CIPMeetingInAirportFactory;
use Module\Booking\Shared\Infrastructure\Factory\Details\CIPSendoffInAirportFactory;
use Module\Booking\Shared\Infrastructure\Factory\Details\DayCarTripFactory;
use Module\Booking\Shared\Infrastructure\Factory\Details\HotelBookingFactory;
use Module\Booking\Shared\Infrastructure\Factory\Details\IntercityTransferFactory;
use Module\Booking\Shared\Infrastructure\Factory\Details\OtherServiceFactory;
use Module\Booking\Shared\Infrastructure\Factory\Details\TransferFromAirportFactory;
use Module\Booking\Shared\Infrastructure\Factory\Details\TransferFromRailwayFactory;
use Module\Booking\Shared\Infrastructure\Factory\Details\TransferToAirportFactory;
use Module\Booking\Shared\Infrastructure\Factory\Details\TransferToRailwayFactory;
use Module\Booking\Shared\Infrastructure\Repository\DetailsRepository;
use Sdk\Module\Support\ServiceProvider;

class DetailsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
//        $this->app->singleton(DetailsRepositoryFactory::class);
        $this->app->singleton(DetailsRepositoryInterface::class, DetailsRepository::class);
        $this->app->singleton(CIPMeetingInAirportFactoryInterface::class, CIPMeetingInAirportFactory::class);
        $this->app->singleton(CIPMeetingInAirportFactoryInterface::class, CIPMeetingInAirportFactory::class);
        $this->app->singleton(CIPSendoffInAirportFactoryInterface::class, CIPSendoffInAirportFactory::class);
        $this->app->singleton(TransferToAirportFactoryInterface::class, TransferToAirportFactory::class);
        $this->app->singleton(TransferFromAirportFactoryInterface::class, TransferFromAirportFactory::class);
        $this->app->singleton(TransferToRailwayFactoryInterface::class, TransferToRailwayFactory::class);
        $this->app->singleton(TransferFromRailwayFactoryInterface::class, TransferFromRailwayFactory::class);
        $this->app->singleton(HotelBookingFactoryInterface::class, HotelBookingFactory::class);
        $this->app->singleton(CarRentWithDriverFactoryInterface::class, CarRentWithDriverFactory::class);
        $this->app->singleton(IntercityTransferFactoryInterface::class, IntercityTransferFactory::class);
        $this->app->singleton(DayCarTripFactoryInterface::class, DayCarTripFactory::class);
        $this->app->singleton(OtherServiceFactoryInterface::class, OtherServiceFactory::class);
        $this->app->singleton(SupplierAdapterInterface::class, SupplierAdapter::class);
    }
}
