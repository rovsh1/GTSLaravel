<?php

namespace Module\Client\Domain\Order\Repository;

use Module\Client\Domain\Order\Order;
use Module\Client\Domain\Order\ValueObject\OrderId;

interface OrderRepositoryInterface
{
    public function find(OrderId $id): Order;

    public function store(Order $id): void;
}