<?php

namespace GTS\Shared\Infrastructure\Providers;

use GTS\Services\EventBus\Infrastructure\Bus\DomainEventDispatcher;
use GTS\Shared\Domain\Event\DomainEventDispatcherInterface;
use GTS\Shared\Infrastructure\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->instance(DomainEventDispatcherInterface::class, DomainEventDispatcher::class);
    }
}
