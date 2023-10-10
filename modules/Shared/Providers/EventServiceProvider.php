<?php

namespace Module\Shared\Providers;

use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Event\DomainEventDispatcher;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(DomainEventDispatcherInterface::class, DomainEventDispatcher::class);
    }
}
