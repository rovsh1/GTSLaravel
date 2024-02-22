<?php

declare(strict_types=1);

namespace Module\Client\Payment\Domain\Order\Repository;

use Module\Client\Payment\Domain\Order\Order;
use Module\Client\Shared\Domain\ValueObject\OrderId;

interface OrderRepositoryInterface
{
    public function find(OrderId $id): ?Order;

    public function findOrFail(OrderId $id): Order;

    public function store(Order $order): void;
}
