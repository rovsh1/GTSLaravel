<?php

namespace Module\Booking\Hotel\Application\Event;

use Module\Booking\Hotel\Domain\Entity\StatusHistoryService;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

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
