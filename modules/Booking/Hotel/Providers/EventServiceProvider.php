<?php

namespace Module\Booking\Hotel\Providers;

use Module\Booking\Common\Domain\Event\StatusEventInterface;
use Module\Booking\Hotel\Application\Event\StatusChangedListener;
use Sdk\Module\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        StatusEventInterface::class => StatusChangedListener::class
    ];

    public function registerListeners($eventDispatcher)
    {
        //$domainEventDispatcher->listen(Req::class, StatusChangedListener::class);
    }
}
