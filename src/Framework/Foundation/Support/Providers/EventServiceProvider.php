<?php

namespace Custom\Framework\Foundation\Support\Providers;

use Custom\Framework\Contracts\Event\IntegrationEventDispatcherInterface;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [];

    public function register()
    {
        $eventDispatcher = $this->app->get(IntegrationEventDispatcherInterface::class);

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
