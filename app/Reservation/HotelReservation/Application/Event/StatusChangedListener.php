<?php

namespace GTS\Reservation\HotelReservation\Application\Event;

use Custom\Framework\Contracts\Event\DomainEventInterface;
use Custom\Framework\Contracts\Event\DomainEventListenerInterface;
use GTS\Reservation\HotelReservation\Domain\Entity\StatusHistoryService;

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
