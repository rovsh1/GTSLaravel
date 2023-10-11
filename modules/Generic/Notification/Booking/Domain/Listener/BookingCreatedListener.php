<?php

namespace Module\Generic\Notification\Booking\Domain\Listener;

use Module\Shared\Contracts\Event\IntegrationEventInterface;
use Module\Shared\Contracts\Event\IntegrationEventListenerInterface;

class BookingCreatedListener implements IntegrationEventListenerInterface
{
    public function handle(IntegrationEventInterface $event): void
    {
        // TODO: Implement handle() method.
    }
}