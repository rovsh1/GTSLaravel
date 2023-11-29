<?php

namespace Module\Booking\EventSourcing\Providers;

use Module\Booking\EventSourcing\Domain\Listener\RegisterEventListener;
use Sdk\Module\Contracts\Event\IntegrationEventSubscriberInterface;
use Sdk\Module\Support\Providers\IntegrationEventServiceProvider as ServiceProvider;
use Sdk\Shared\Event\IntegrationEventMessages;

class IntegrationEventServiceProvider extends ServiceProvider
{
    protected array $listen = [
//        IntegrationEventMessages::BOOKING_STATUS_UPDATED => StatusUpdatedListener::class,
//        IntegrationEventMessages::BOOKING_REQUEST_SENT => StatusUpdatedListener::class,
//        IntegrationEventMessages::BOOKING_MODIFIED => BookingChangesListener::class,
//        IntegrationEventMessages::BOOKING_DETAILS_MODIFIED => BookingChangesListener::class,
    ];

    protected array $registerChangesEvents = [
        IntegrationEventMessages::BOOKING_STATUS_UPDATED,
        IntegrationEventMessages::HOTEL_BOOKING_ACCOMMODATION_ADDED,
        IntegrationEventMessages::HOTEL_BOOKING_ACCOMMODATION_REMOVED,
        IntegrationEventMessages::HOTEL_BOOKING_ACCOMMODATION_DETAILS_EDITED,
        IntegrationEventMessages::HOTEL_BOOKING_ACCOMMODATION_REPLACED,
        IntegrationEventMessages::HOTEL_BOOKING_GUEST_ADDED,
        IntegrationEventMessages::HOTEL_BOOKING_GUEST_REMOVED,
    ];

    protected string $changesListener = RegisterEventListener::class;

    protected function registerListeners(IntegrationEventSubscriberInterface $integrationEventSubscriber): void
    {
        parent::registerListeners($integrationEventSubscriber);

        foreach ($this->registerChangesEvents as $eventClass) {
            $integrationEventSubscriber->listen($eventClass, $this->changesListener);
        }
    }
}
