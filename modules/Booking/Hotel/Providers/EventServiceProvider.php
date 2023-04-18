<?php

namespace Module\Booking\Hotel\Providers;

use Custom\Framework\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Module\Booking\Common\Domain\Event\StatusEventInterface;
use Module\Booking\Hotel\Application\Event\StatusChangedListener;

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
