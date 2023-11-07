<?php

namespace Module\Booking\Shared\Providers\ServiceBooking;

use Module\Booking\Shared\Domain\Booking\Listener\BookingChangesListener;
use Module\Booking\Shared\Domain\Shared\Event\BookingEventInterface;
use Sdk\Module\Support\Providers\DomainEventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        BookingEventInterface::class => [
            BookingChangesListener::class
        ]
    ];
}
