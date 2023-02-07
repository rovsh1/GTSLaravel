<?php

namespace GTS\Integration\Traveline\Application\Event;

use Custom\Framework\Event\DomainEventInterface;
use Custom\Framework\Event\DomainEventListenerInterface;
use GTS\Services\Traveline\Infrastructure\Adapter\Traveline\AdapterInterface;

class SendReservationNotificationListener implements DomainEventListenerInterface
{
    public function __construct(private AdapterInterface $adapter) {}

    public function handle(DomainEventInterface $event)
    {
        $this->adapter->sendReservationNotification();
    }
}
