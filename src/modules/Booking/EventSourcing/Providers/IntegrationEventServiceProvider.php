<?php

namespace Module\Booking\EventSourcing\Providers;

use Module\Booking\EventSourcing\Domain\Listener\BookingChangesListener;
use Module\Booking\EventSourcing\Domain\Listener\StatusUpdatedListener;
use Module\Shared\Support\Event\IntegrationEventMessages;
use Sdk\Module\Support\Providers\IntegrationEventServiceProvider as ServiceProvider;

class IntegrationEventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        IntegrationEventMessages::BOOKING_STATUS_UPDATED => StatusUpdatedListener::class,
//        IntegrationEventMessages::BOOKING_REQUEST_SENT => StatusUpdatedListener::class,
        IntegrationEventMessages::BOOKING_MODIFIED => BookingChangesListener::class,
//        IntegrationEventMessages::BOOKING_DETAILS_MODIFIED => BookingChangesListener::class,
    ];
}
