<?php

namespace Module\Booking\Providers\HotelBooking;

use Module\Booking\Domain\HotelBooking\Adapter\HotelAdapterInterface;
use Module\Booking\Domain\HotelBooking\Adapter\HotelRoomAdapterInterface;
use Module\Booking\Domain\HotelBooking\Adapter\HotelRoomQuotaAdapterInterface;
use Module\Booking\Infrastructure\HotelBooking\Adapter\HotelAdapter;
use Module\Booking\Infrastructure\HotelBooking\Adapter\HotelRoomAdapter;
use Module\Booking\Infrastructure\HotelBooking\Adapter\HotelRoomQuotaAdapter;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class AdapterServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton(HotelAdapterInterface::class, HotelAdapter::class);
        $this->app->singleton(HotelRoomAdapterInterface::class, HotelRoomAdapter::class);
        $this->app->singleton(HotelRoomQuotaAdapterInterface::class, HotelRoomQuotaAdapter::class);
    }
}
