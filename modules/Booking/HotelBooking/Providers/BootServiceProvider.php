<?php

namespace Module\Booking\HotelBooking\Providers;

use Module\Booking\Common\Domain\Service\BookingCalculatorInterface;
use Module\Booking\HotelBooking\Domain\Repository\BookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Repository\RoomBookingRepositoryInterface;
use Module\Booking\HotelBooking\Domain\Service\PriceCalculator\BookingCalculator;
use Module\Booking\HotelBooking\Infrastructure\Repository\BookingRepository;
use Module\Booking\HotelBooking\Infrastructure\Repository\RoomBookingRepository;
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
        //TODO common?
        $this->app->singleton(BookingCalculatorInterface::class, BookingCalculator::class);
//        $this->app->singleton(
//            Domain\Repository\RoomRepositoryInterface::class,
//            Infrastructure\Repository\RoomRepository::class
//        );
    }
}
