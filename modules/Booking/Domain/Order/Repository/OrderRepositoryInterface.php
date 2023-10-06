<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Order\Repository;

use Module\Booking\Domain\Order\Order;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

interface OrderRepositoryInterface
{
    public function create(int $clientId, int $currencyId, ?int $legalId = null): Order;

    public function find(int $id): ?Order;

    /**
     * @param int $id
     * @return Order
     * @throws EntityNotFoundException
     */
    public function findOrFail(int $id): Order;

    /**
     * @return Order[]
     */
    public function getActiveOrders(int|null $clientId): array;

    public function store(Order $order): bool;
}
