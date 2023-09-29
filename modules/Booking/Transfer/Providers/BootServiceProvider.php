<?php

namespace Module\Booking\Transfer\Providers;

use Module\Booking\HotelBooking\Infrastructure\Repository\BookingGuestRepository;
use Module\Booking\HotelBooking\Domain\Repository\BookingQuotaRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Repository\BookingGuestRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Repository\RoomBookingRepositoryInterface;
use Module\Booking\HotelBooking\Infrastructure\Repository\BookingQuotaRepository;
use Module\Booking\HotelBooking\Infrastructure\Repository\BookingRepository;
use Module\Booking\HotelBooking\Infrastructure\Repository\RoomBookingRepository;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class BootServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(EventServiceProvider::class);
    }
}
