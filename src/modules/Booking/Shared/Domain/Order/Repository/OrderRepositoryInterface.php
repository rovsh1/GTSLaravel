<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Order\Repository;

use Module\Booking\Shared\Domain\Order\Order;
use Sdk\Booking\ValueObject\ClientId;
use Sdk\Booking\ValueObject\CreatorId;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;
use Sdk\Shared\Enum\CurrencyEnum;

interface OrderRepositoryInterface
{
    public function find(OrderId $id): ?Order;

    /**
     * @param int $id
     * @return Order
     * @throws EntityNotFoundException
     */
    public function findOrFail(OrderId $id): Order;

    /**
     * @return Order[]
     */
    public function getActiveOrders(int|null $clientId): array;
}
