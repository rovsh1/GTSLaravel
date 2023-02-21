<?php

namespace Module\Integration\Traveline\Application\Event;

use Custom\Framework\Contracts\Event\DomainEventInterface;
use Custom\Framework\Contracts\Event\DomainEventListenerInterface;
use GTS\Services\Traveline\Infrastructure\Adapter\Traveline\AdapterInterface;

class SendReservationNotificationListener implements DomainEventListenerInterface
{
    public function __construct(private AdapterInterface $adapter) {}

    public function handle(DomainEventInterface $event)
    {
        $this->adapter->sendReservationNotification();
    }
}
