<?php

namespace Module\Booking\Providers\ServiceBooking;

use Module\Booking\Domain\Booking\Listener\BookingChangesListener;
use Module\Booking\Domain\Shared\Event\BookingEventInterface;
use Sdk\Module\Support\Providers\DomainEventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        BookingEventInterface::class => [
            BookingChangesListener::class
        ]
    ];
}
