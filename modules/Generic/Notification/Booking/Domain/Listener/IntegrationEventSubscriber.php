<?php

namespace Module\Generic\Notification\Booking\Domain\Listener;

use Module\Shared\Contracts\Event\IntegrationEventInterface;
use Module\Shared\IntegrationEvent\Booking\BookingCreated;

class IntegrationEventSubscriber
{
    protected array $listen = [
        BookingCreated::class => BookingCreatedListener::class
    ];

    public function handle(IntegrationEventInterface $event): void
    {
        if (!isset($this->listen[$event::class])) {
            return;
        }

        $listener = $this->listen[$event::class];
        $this->handleListener(is_array($listener) ? $listener : [$listener], $event);
    }

    protected function handleListener(array $listeners, IntegrationEventInterface $event): void
    {
        foreach ($listeners as $listener) {
            $listener->handle($event);
        }
    }
}