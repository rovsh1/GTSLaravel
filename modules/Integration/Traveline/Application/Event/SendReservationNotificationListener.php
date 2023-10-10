<?php

namespace Module\Integration\Traveline\Application\Event;

use Module\Integration\Traveline\Domain\Adapter\TravelineAdapterInterface;
use Module\Shared\Contracts\Event\IntegrationEventInterface;
use Module\Shared\Contracts\Event\IntegrationEventListenerInterface;

class SendReservationNotificationListener implements IntegrationEventListenerInterface
{
    public function __construct(private TravelineAdapterInterface $adapter) {}

    public function handle(IntegrationEventInterface $event)
    {
        $this->adapter->sendReservationNotification();
    }
}
