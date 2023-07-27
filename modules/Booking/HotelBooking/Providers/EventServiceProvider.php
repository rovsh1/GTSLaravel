<?php

namespace Module\Booking\HotelBooking\Providers;

use Module\Booking\Common\Domain\Event\BookingEventInterface;
use Module\Booking\Common\Domain\Event\Contracts\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Common\Domain\Event\Status\BookingPaid;
use Module\Booking\HotelBooking\Domain\Listener\BookingChangesListener;
use Module\Booking\HotelBooking\Domain\Listener\GenerateBookingInvoiceListener;
use Module\Booking\HotelBooking\Domain\Listener\RecalculateBookingPricesListener;
use Sdk\Module\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        BookingEventInterface::class => BookingChangesListener::class,
        PriceBecomeDeprecatedEventInterface::class => RecalculateBookingPricesListener::class,
        BookingPaid::class => GenerateBookingInvoiceListener::class,
    ];

//    public function registerListeners($eventDispatcher)
//    {
//        //$domainEventDispatcher->listen(Req::class, StatusChangedListener::class);
//    }
}