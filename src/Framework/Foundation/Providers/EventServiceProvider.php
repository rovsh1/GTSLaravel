<?php

namespace Custom\Framework\Foundation\Providers;

use Custom\Framework\Event\DomainEventDispatcher;
use Custom\Framework\Event\DomainEventDispatcherInterface;
use Custom\Framework\Foundation\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->instance(DomainEventDispatcherInterface::class, DomainEventDispatcher::class);
    }
}
