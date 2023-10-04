<?php

namespace Module\Booking\Providers\HotelBooking;

use Module\Booking\Domain\HotelBooking\Repository\BookingGuestRepositoryInterface;
use Module\Booking\Domain\HotelBooking\Repository\BookingQuotaRepositoryInterface;
use Module\Booking\Domain\HotelBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\HotelBooking\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Infrastructure\HotelBooking\Repository\BookingGuestRepository;
use Module\Booking\Infrastructure\HotelBooking\Repository\BookingQuotaRepository;
use Module\Booking\Infrastructure\HotelBooking\Repository\BookingRepository;
use Module\Booking\Infrastructure\HotelBooking\Repository\RoomBookingRepository;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(AdapterServiceProvider::class);
        $this->app->register(DocumentGeneratorServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
    }

    public function boot()
    {
        $this->app->singleton(RoomBookingRepositoryInterface::class, RoomBookingRepository::class);
        $this->app->singleton(BookingRepositoryInterface::class, BookingRepository::class);
        $this->app->singleton(BookingQuotaRepositoryInterface::class, BookingQuotaRepository::class);
        $this->app->singleton(BookingGuestRepositoryInterface::class, BookingGuestRepository::class);
//        $this->app->singleton(
//            Domain\Repository\RoomRepositoryInterface::class,
//            Infrastructure\Repository\RoomRepository::class
//        );
    }
}
