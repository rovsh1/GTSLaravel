<?php

namespace Module\Generic\Logging\Providers;

use Module\Booking\Application\Admin\Shared\Event\TestEvent;
use Module\Generic\Logging\Listener\TestListener;
use Sdk\Module\Support\Providers\IntegrationEventServiceProvider as ServiceProvider;

class IntegrationEventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        TestEvent::class => TestListener::class
    ];
}
