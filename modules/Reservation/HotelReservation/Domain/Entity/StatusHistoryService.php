<?php

namespace Module\Reservation\HotelReservation\Domain\Entity;

use Module\Reservation\Common\Domain\Event\EventInterface;
use Module\Reservation\HotelReservation\Domain\Repository\RequestRepositoryInterface;

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
