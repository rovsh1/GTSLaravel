<?php

namespace Module\Booking\Providers;

use Module\Booking\Domain\Booking\Repository\AirportBookingGuestRepositoryInterface;
use Module\Booking\Domain\Booking\Service\StatusStorageInterface;
use Module\Booking\Domain\BookingRequest\Adapter\AirportAdapterInterface;
use Module\Booking\Domain\BookingRequest\Adapter\CityAdapterInterface;
use Module\Booking\Domain\BookingRequest\Adapter\RailwayStationAdapterInterface;
use Module\Booking\Domain\Shared\Adapter\AdministratorAdapterInterface;
use Module\Booking\Domain\Shared\Adapter\ClientAdapterInterface;
use Module\Booking\Domain\Shared\Adapter\CountryAdapterInterface;
use Module\Booking\Domain\Shared\Repository\BookingChangesLogRepositoryInterface;
use Module\Booking\Infrastructure\Adapter\AdministratorAdapter;
use Module\Booking\Infrastructure\Adapter\AirportAdapter;
use Module\Booking\Infrastructure\Adapter\CityAdapter;
use Module\Booking\Infrastructure\Adapter\ClientAdapter;
use Module\Booking\Infrastructure\Adapter\CountryAdapter;
use Module\Booking\Infrastructure\Adapter\RailwayStationAdapter;
use Module\Booking\Infrastructure\Repository\BookingChangesLogRepository;
use Module\Booking\Infrastructure\Service\StatusStorage;
use Module\Booking\Infrastructure\ServiceBooking\Repository\AirportAirportBookingGuestRepository;
use Sdk\Module\Support\ServiceProvider;

class SharedServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(StatusStorageInterface::class, StatusStorage::class);
        $this->app->singleton(AdministratorAdapterInterface::class, AdministratorAdapter::class);
        $this->app->singleton(ClientAdapterInterface::class, ClientAdapter::class);
        $this->app->singleton(CountryAdapterInterface::class, CountryAdapter::class);

        $this->app->singleton(BookingChangesLogRepositoryInterface::class, BookingChangesLogRepository::class);

        $this->app->singleton(AirportBookingGuestRepositoryInterface::class, AirportAirportBookingGuestRepository::class);
        $this->app->singleton(AirportAdapterInterface::class, AirportAdapter::class);
        $this->app->singleton(RailwayStationAdapterInterface::class, RailwayStationAdapter::class);
        $this->app->singleton(CityAdapterInterface::class, CityAdapter::class);
    }
}
