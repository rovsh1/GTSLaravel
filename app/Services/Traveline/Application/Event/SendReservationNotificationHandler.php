<?php

namespace GTS\Services\Traveline\Application\Event;

use GTS\Services\Traveline\Infrastructure\Adapter\Traveline\AdapterInterface;
use GTS\Shared\Application\Event\EventHandlerInterface;
use GTS\Shared\Domain\Event\EventInterface;

class SendReservationNotificationHandler implements EventHandlerInterface
{
    public function __construct(private AdapterInterface $adapter) {}

    public function handle(EventInterface $event)
    {
        $this->adapter->sendReservationNotification();
    }
}
