<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline;

use Illuminate\Support\ServiceProvider;
use Pkg\Supplier\Traveline\Contracts\IntegrationEventDispatcherInterface;
use Pkg\Supplier\Traveline\Listener\StoreTravelineReservation;
use Pkg\Supplier\Traveline\Listener\SendBookingNotificationListener;
use Pkg\Supplier\Traveline\Support\EventDispatcher\IntegrationEventDispatcher;
use Sdk\Booking\IntegrationEvent\BookingEventInterface;

class EventServiceProvider extends ServiceProvider
{
    protected array $listen = [
        BookingEventInterface::class => [
            StoreTravelineReservation::class,
            SendBookingNotificationListener::class,
        ]
    ];

    public function register(): void
    {
        $this->app->resolving(IntegrationEventDispatcherInterface::class, function ($dispatcher) {
            $this->registerListeners($dispatcher);
        });
    }

    public function boot(): void
    {
        $this->app->singleton(IntegrationEventDispatcherInterface::class, IntegrationEventDispatcher::class);
    }

    protected function registerListeners(IntegrationEventDispatcherInterface $dispatcher): void
    {
        foreach ($this->listen as $eventClass => $listeners) {
            if (is_array($listeners)) {
                foreach ($listeners as $listener) {
                    $dispatcher->listen($eventClass, $listener);
                }
            } else {
                $dispatcher->listen($eventClass, $listeners);
            }
        }
    }
}
