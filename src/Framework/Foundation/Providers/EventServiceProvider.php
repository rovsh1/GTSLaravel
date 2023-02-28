<?php

namespace Custom\Framework\Foundation\Providers;

use Custom\Framework\Contracts\Event\DomainEventDispatcherInterface;
use Custom\Framework\Contracts\Event\DomainEventHandlerInterface;
use Custom\Framework\Contracts\Event\IntegrationEventDispatcherInterface;
use Custom\Framework\Contracts\Event\IntegrationEventHandlerInterface;
use Custom\Framework\Event\DomainEventDispatcher;
use Custom\Framework\Event\DomainEventHandler;
use Custom\Framework\Event\IntegrationEventDispatcher;
use Custom\Framework\Foundation\Support\Providers\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(DomainEventDispatcherInterface::class, function ($module) {
            return new DomainEventDispatcher($module, $module->get(DomainEventHandlerInterface::class));
        });

        $this->app->singleton(DomainEventHandlerInterface::class, function ($module) {
            return new DomainEventHandler($module, $module->get(IntegrationEventHandlerInterface::class));
        });

        $this->app->singleton(IntegrationEventDispatcherInterface::class, function ($module) {
            return new IntegrationEventDispatcher($module);
        });
    }
}
