<?php

namespace Sdk\Module\Foundation\Providers;

use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Contracts\Event\DomainEventHandlerInterface;
use Sdk\Module\Contracts\Event\IntegrationEventDispatcherInterface;
use Sdk\Module\Contracts\Event\IntegrationEventHandlerInterface;
use Sdk\Module\Event\DomainEventDispatcher;
use Sdk\Module\Event\DomainEventHandler;
use Sdk\Module\Event\IntegrationEventDispatcher;

class EventServiceProvider extends \Sdk\Module\Foundation\Support\Providers\ServiceProvider
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
