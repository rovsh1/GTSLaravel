<?php

namespace Module\Booking\Hotel\Domain\Entity;

use Module\Booking\Common\Domain\Event\EventInterface;
use Module\Booking\Hotel\Domain\Repository\RequestRepositoryInterface;

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
