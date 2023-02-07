<?php

namespace GTS\Shared\Infrastructure\Support;

use GTS\Shared\Domain\Event\DomainEventDispatcherInterface;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [];

    public function boot()
    {
        $eventDispatcher = $this->app->get(DomainEventDispatcherInterface::class);

        $this->registerListeners($eventDispatcher);
    }

    protected function registerListeners($eventDispatcher)
    {
        foreach ($this->listen as $eventClass => $listeners) {
            if (is_array($listeners)) {
                foreach ($listeners as $listenerClass) {
                    $eventDispatcher->listen($eventClass, $listenerClass);
                }
            } else {
                $eventDispatcher->listen($eventClass, $listeners);
            }
        }
    }
}
