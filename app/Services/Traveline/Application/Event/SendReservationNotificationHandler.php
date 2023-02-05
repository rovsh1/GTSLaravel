<?php

namespace GTS\Services\Traveline\Application\Event;

use GTS\Services\Traveline\Infrastructure\Adapter\Traveline\AdapterInterface;
use GTS\Shared\Application\Event\EventHandlerInterface;
use GTS\Shared\Domain\Event\DomainEventInterface;

class SendReservationNotificationHandler implements EventHandlerInterface
{
    public function __construct(private AdapterInterface $adapter) {}

    public function handle(DomainEventInterface $event)
    {
        $this->adapter->sendReservationNotification();
    }
}
