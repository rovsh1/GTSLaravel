<?php

namespace Module\Client\Invoicing\Domain\Order\Repository;

use Module\Client\Invoicing\Domain\Order\Order;
use Module\Client\Invoicing\Domain\Order\ValueObject\OrderId;

interface OrderRepositoryInterface
{
    public function find(OrderId $id): ?Order;

    public function findOrFail(OrderId $id): Order;

    public function store(Order $order): void;
}
