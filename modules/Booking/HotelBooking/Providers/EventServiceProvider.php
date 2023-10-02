<?php

namespace Module\Booking\HotelBooking\Providers;

use Module\Booking\Common\Domain\Event\BookingDeleted;
use Module\Booking\Common\Domain\Event\BookingEventInterface;
use Module\Booking\Common\Domain\Event\BookingStatusEventInterface;
use Module\Booking\HotelBooking\Domain\Event\PriceBecomeDeprecatedEventInterface;
use Module\Booking\HotelBooking\Domain\Listener\BookingChangesListener;
use Module\Booking\HotelBooking\Domain\Listener\BookingQuotaUpdaterListener;
use Module\Booking\HotelBooking\Domain\Listener\RecalculateBookingPricesListener;
use Sdk\Module\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        BookingEventInterface::class => BookingChangesListener::class,
        PriceBecomeDeprecatedEventInterface::class => RecalculateBookingPricesListener::class,
        //обновление квот
        BookingStatusEventInterface::class => BookingQuotaUpdaterListener::class,
        BookingDeleted::class => BookingQuotaUpdaterListener::class,
    ];

//    public function registerListeners($eventDispatcher)
//    {
//        //$domainEventDispatcher->listen(Req::class, StatusChangedListener::class);
//    }
}
