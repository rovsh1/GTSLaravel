<?php

namespace Module\Booking\HotelBooking\Providers;

use Module\Booking\HotelBooking\Domain\Adapter\HotelAdapterInterface;
use Module\Booking\HotelBooking\Domain\Adapter\HotelRoomAdapterInterface;
use Module\Booking\HotelBooking\Infrastructure\Adapter\HotelAdapter;
use Module\Booking\HotelBooking\Infrastructure\Adapter\HotelRoomAdapter;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class AdapterServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(HotelAdapterInterface::class, HotelAdapter::class);
        $this->app->singleton(HotelRoomAdapterInterface::class, HotelRoomAdapter::class);
    }
}
