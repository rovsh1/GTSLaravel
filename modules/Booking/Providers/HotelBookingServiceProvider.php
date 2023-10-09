<?php

namespace Module\Booking\Providers;

use Module\Booking\Deprecated\HotelBooking\Adapter\HotelAdapterInterface;
use Module\Booking\Deprecated\HotelBooking\Adapter\HotelRoomAdapterInterface;
use Module\Booking\Deprecated\HotelBooking\Adapter\HotelRoomQuotaAdapterInterface;
use Module\Booking\Domain\Booking\Adapter\HotelPricingAdapterInterface;
use Module\Booking\Domain\Booking\Repository\RoomBookingRepositoryInterface;
use Module\Booking\Infrastructure\Adapter\HotelPricingAdapter;
use Module\Booking\Infrastructure\HotelBooking\Adapter\HotelAdapter;
use Module\Booking\Infrastructure\HotelBooking\Adapter\HotelRoomAdapter;
use Module\Booking\Infrastructure\HotelBooking\Adapter\HotelRoomQuotaAdapter;
use Module\Booking\Infrastructure\HotelBooking\Repository\RoomBookingRepository;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class HotelBookingServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot(): void
    {
        $this->app->singleton(HotelAdapterInterface::class, HotelAdapter::class);
        $this->app->singleton(HotelRoomAdapterInterface::class, HotelRoomAdapter::class);
        $this->app->singleton(HotelRoomQuotaAdapterInterface::class, HotelRoomQuotaAdapter::class);
        $this->app->singleton(HotelPricingAdapterInterface::class, HotelPricingAdapter::class);

        $this->app->singleton(RoomBookingRepositoryInterface::class, RoomBookingRepository::class);
//        $this->app->singleton(BookingQuotaRepositoryInterface::class, BookingQuotaRepository::class);
//        $this->app->singleton(BookingGuestRepositoryInterface::class, BookingGuestRepository::class);
//        $this->app->singleton(
//            Domain\Repository\RoomRepositoryInterface::class,
//            Infrastructure\Repository\RoomRepository::class
//        );
    }
}
