<?php

declare(strict_types=1);

namespace Module\Booking\Order\Domain\Repository;

use Module\Booking\Order\Domain\Entity\Order;

interface OrderRepositoryInterface
{
    public function create(int $clientId, int $currencyId, ?int $legalId = null): Order;

    public function find(int $id): ?Order;

    /**
     * @return Order[]
     */
    public function getActiveOrders(): array;

    public function store(Order $order): bool;
}
