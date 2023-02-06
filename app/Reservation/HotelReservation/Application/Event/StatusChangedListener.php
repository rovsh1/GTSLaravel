<?php

namespace GTS\Reservation\HotelReservation\Application\Event;

use GTS\Reservation\HotelReservation\Domain\Entity\StatusHistoryService;
use GTS\Shared\Application\Event\DomainEventListenerInterface;
use GTS\Shared\Domain\Event\DomainEventInterface;

class StatusChangedListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly StatusHistoryService $statusHistory
    ) {}

    public function handle(DomainEventInterface $event)
    {
        $this->statusHistory->registerEvent($event);
    }
}
