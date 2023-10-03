<?php

namespace Module\Booking\Providers\HotelBooking;

use Module\Booking\Domain\HotelBooking\Event\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Domain\HotelBooking\Listener\BookingChangesListener;
use Module\Booking\Domain\HotelBooking\Listener\BookingQuotaUpdaterListener;
use Module\Booking\Domain\HotelBooking\Listener\RecalculateBookingPricesListener;
use Module\Booking\Domain\Shared\Event\BookingDeleted;
use Module\Booking\Domain\Shared\Event\BookingEventInterface;
use Module\Booking\Domain\Shared\Event\BookingStatusEventInterface;
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
