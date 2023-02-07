<?php

namespace GTS\Shared\Custom\Foundation\Providers;

use GTS\Services\EventBus\Infrastructure\Bus\DomainEventDispatcher;
use GTS\Shared\Domain\Event\DomainEventDispatcherInterface;
use GTS\Shared\Infrastructure\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->instance(DomainEventDispatcherInterface::class, DomainEventDispatcher::class);
    }
}
