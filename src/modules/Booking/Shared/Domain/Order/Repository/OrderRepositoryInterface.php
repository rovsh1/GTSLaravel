<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Order\Repository;

use Module\Booking\Shared\Domain\Order\Order;
use Module\Booking\Shared\Domain\Order\ValueObject\ClientId;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;
use Module\Booking\Shared\Domain\Shared\ValueObject\CreatorId;
use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;

interface OrderRepositoryInterface
{
    public function create(ClientId $clientId, CurrencyEnum $currency, CreatorId $creatorId, ?int $legalId = null): Order;

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

    public function store(Order $order): bool;
}
