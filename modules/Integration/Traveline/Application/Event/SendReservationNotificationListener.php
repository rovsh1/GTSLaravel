<?php

namespace Module\Integration\Traveline\Application\Event;

use Custom\Framework\Contracts\Event\IntegrationEventInterface;
use Custom\Framework\Contracts\Event\IntegrationEventListenerInterface;
use Module\Integration\Traveline\Domain\Adapter\TravelineAdapterInterface;

class SendReservationNotificationListener implements IntegrationEventListenerInterface
{
    public function __construct(private TravelineAdapterInterface $adapter) {}

    public function handle(IntegrationEventInterface $event)
    {
        $this->adapter->sendReservationNotification();
    }
}
