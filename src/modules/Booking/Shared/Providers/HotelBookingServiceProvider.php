<?php

namespace Module\Booking\Shared\Providers;

use Module\Booking\Shared\Domain\Booking\Adapter\HotelAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Adapter\HotelRoomAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Adapter\HotelQuotaAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Repository\HotelBooking\BookingGuestRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Module\Booking\Shared\Infrastructure\HotelBooking\Adapter\HotelAdapter;
use Module\Booking\Shared\Infrastructure\HotelBooking\Adapter\HotelRoomAdapter;
use Module\Booking\Shared\Infrastructure\HotelBooking\Adapter\HotelQuotaAdapter;
use Module\Booking\Shared\Infrastructure\HotelBooking\Repository\BookingGuestRepository;
use Module\Booking\Shared\Infrastructure\HotelBooking\Repository\AccommodationRepository;
use Sdk\Module\Support\ServiceProvider;

class HotelBookingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->singleton(HotelAdapterInterface::class, HotelAdapter::class);
        $this->app->singleton(HotelRoomAdapterInterface::class, HotelRoomAdapter::class);
        $this->app->singleton(HotelQuotaAdapterInterface::class, HotelQuotaAdapter::class);

        $this->app->singleton(AccommodationRepositoryInterface::class, AccommodationRepository::class);
        $this->app->singleton(BookingGuestRepositoryInterface::class, BookingGuestRepository::class);
//        $this->app->singleton(BookingQuotaRepositoryInterface::class, BookingQuotaRepository::class);
//        $this->app->singleton(BookingGuestRepositoryInterface::class, BookingGuestRepository::class);
//        $this->app->singleton(
//            Domain\Repository\RoomRepositoryInterface::class,
//            Infrastructure\Repository\RoomRepository::class
//        );
    }
}
