<?php

namespace Pkg\Booking\EventSourcing\Providers;

use Pkg\Booking\EventSourcing\Domain\Listener\RegisterEventListener;
use Sdk\Booking\IntegrationEvent\BookingEventInterface;
use Sdk\Module\Support\Providers\IntegrationEventServiceProvider as ServiceProvider;

class IntegrationEventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        BookingEventInterface::class => RegisterEventListener::class
    ];
}
