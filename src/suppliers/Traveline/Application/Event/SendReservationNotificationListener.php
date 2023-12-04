<?php

namespace Supplier\Traveline\Application\Event;

use Module\Integration\Traveline\Application\Event\IntegrationEventInterface;
use Module\Integration\Traveline\Application\Event\IntegrationEventListenerInterface;
use Supplier\Traveline\Domain\Adapter\TravelineAdapterInterface;

class SendReservationNotificationListener implements IntegrationEventListenerInterface
{
    public function __construct(private TravelineAdapterInterface $adapter) {}

    public function handle(IntegrationEventInterface $event)
    {
        $this->adapter->sendReservationNotification();
    }
}
