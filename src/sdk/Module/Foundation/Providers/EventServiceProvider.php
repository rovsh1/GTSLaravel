<?php

namespace Sdk\Module\Foundation\Providers;

use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;
use Sdk\Module\Event\DomainEventDispatcher;
use Sdk\Module\Foundation\Support\Providers\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(DomainEventDispatcherInterface::class, function ($module) {
            return new DomainEventDispatcher(
                $module,
//                $module->get(DomainEventHandlerInterface::class)
            );
        });
    }
}
