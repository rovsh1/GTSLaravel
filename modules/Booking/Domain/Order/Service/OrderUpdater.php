<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Order\Service;

use Module\Booking\Domain\Order\Order;
use Module\Booking\Domain\Order\Repository\OrderRepositoryInterface;
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