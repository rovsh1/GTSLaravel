<?php

namespace GTS\Services\Integration\Traveline\Application\Event;

use GTS\Services\Traveline\Infrastructure\Adapter\Traveline\AdapterInterface;
use GTS\Shared\Application\Event\DomainEventListenerInterface;
use GTS\Shared\Domain\Event\DomainEventInterface;

class SendReservationNotificationListener implements DomainEventListenerInterface
{
    public function __construct(private AdapterInterface $adapter) {}

    public function handle(DomainEventInterface $event)
    {
        $this->adapter->sendReservationNotification();
    }
}
