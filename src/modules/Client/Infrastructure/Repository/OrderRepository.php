<?php

declare(strict_types=1);

namespace Module\Client\Infrastructure\Repository;

use Module\Client\Domain\Order\Exception\OrderNotFoundException;
use Module\Client\Domain\Order\Order;
use Module\Client\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Client\Domain\Order\ValueObject\OrderId;

class OrderRepository implements OrderRepositoryInterface
{
    public function find(OrderId $id): ?Order
    {
        // TODO: Implement find() method.
    }

    public function findOrFail(OrderId $id): Order
    {
        return $this->find($id) ?? throw new OrderNotFoundException();
    }

    public function store(Order $id): void
    {
        // TODO: Implement store() method.
    }
}
