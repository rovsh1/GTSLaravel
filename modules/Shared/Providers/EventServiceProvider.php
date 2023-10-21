<?php

namespace Module\Shared\Providers;

use Sdk\Module\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function register(): void
    {
//        $this->app->singleton(DomainEventDispatcherInterface::class, DomainEventDispatcher::class);
    }
}
