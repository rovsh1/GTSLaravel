<?php

namespace Module\Booking\EventSourcing\Providers;

use Module\Booking\EventSourcing\Domain\Listener\RegisterEventListener;
use Sdk\Module\Support\Providers\IntegrationEventServiceProvider as ServiceProvider;
use Sdk\Booking\IntegrationEvent\BookingEventInterface;

class IntegrationEventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        BookingEventInterface::class => RegisterEventListener::class
    ];
}
