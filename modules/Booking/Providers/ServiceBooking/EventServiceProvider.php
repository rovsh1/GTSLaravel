<?php

namespace Module\Booking\Providers\ServiceBooking;

use Sdk\Module\Support\Providers\DomainEventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected array $listen = [];
}
