<?php

declare(strict_types=1);

namespace Module\Booking\Order\Domain\Service;

use Module\Booking\Order\Domain\Entity\Order;
use Module\Booking\Order\Domain\Repository\OrderRepositoryInterface;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;

class OrderUpdater
{
    public function __construct(
        private readonly OrderRepositoryInterface $repository,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function store(Order $order): bool
    {
        $success = $this->repository->store($order);
        $this->eventDispatcher->dispatch(...$order->pullEvents());

        return $success;
    }
}
