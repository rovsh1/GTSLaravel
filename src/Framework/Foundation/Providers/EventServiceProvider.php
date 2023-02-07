<?php

namespace Custom\Framework\Foundation\Providers;

use Custom\Framework\Contracts\Event\DomainEventDispatcherInterface;
use Custom\Framework\Event\DomainEventDispatcher;
use Custom\Framework\Foundation\Support\Providers\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->instance(DomainEventDispatcherInterface::class, DomainEventDispatcher::class);
    }
}
