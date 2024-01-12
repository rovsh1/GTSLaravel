<?php

namespace Pkg\Booking\Requesting\Providers;

use Pkg\Booking\Requesting\Domain\Listener\ClearChangesListener;
use Pkg\Booking\Requesting\Domain\Listener\RegisterChangesListener;
use Sdk\Booking\IntegrationEvent\BookingEventInterface;
use Sdk\Booking\IntegrationEvent\StatusUpdated;
use Sdk\Module\Support\Providers\IntegrationEventServiceProvider as ServiceProvider;

class IntegrationEventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        BookingEventInterface::class => RegisterChangesListener::class,
        StatusUpdated::class => ClearChangesListener::class
    ];
}
