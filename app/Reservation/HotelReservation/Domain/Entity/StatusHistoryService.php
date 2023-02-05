<?php

namespace GTS\Reservation\HotelReservation\Domain\Entity;

use GTS\Reservation\Common\Domain\Event\EventInterface;
use GTS\Reservation\HotelReservation\Domain\Repository\RequestRepositoryInterface;

class StatusHistoryService
{
    public function __construct(
        private readonly RequestRepositoryInterface $statusRepository,
    ) {}

    public function registerEvent(EventInterface $event): void
    {
        $this->statusRepository->create($event->id, $event::class);
    }
}
