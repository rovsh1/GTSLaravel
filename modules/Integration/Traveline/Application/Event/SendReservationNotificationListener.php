<?php

namespace Module\Integration\Traveline\Application\Event;

use Custom\Framework\Contracts\Event\DomainEventInterface;
use Custom\Framework\Contracts\Event\DomainEventListenerInterface;
use Module\Integration\Traveline\Domain\Adapter\TravelineAdapterInterface;

class SendReservationNotificationListener implements DomainEventListenerInterface
{
    public function __construct(private TravelineAdapterInterface $adapter) {}

    public function handle(DomainEventInterface $event)
    {
        $this->adapter->sendReservationNotification();
    }
}
