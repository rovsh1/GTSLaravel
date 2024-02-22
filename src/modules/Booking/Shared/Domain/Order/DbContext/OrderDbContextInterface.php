<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Order\DbContext;

use Module\Booking\Shared\Domain\Order\Order;
use Sdk\Booking\ValueObject\ClientId;
use Sdk\Booking\ValueObject\CreatorId;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Shared\Enum\CurrencyEnum;

interface OrderDbContextInterface
{
    public function create(
        ClientId $clientId,
        CurrencyEnum $currency,
        CreatorId $creatorId,
        ?int $legalId = null,
        ?string $note = null
    ): Order;

    public function find(OrderId $id): ?Order;

    /**
     * @return Order[]
     */
    public function getActiveOrders(int|null $clientId): array;

    public function store(Order $order): void;

    public function touch(OrderId $id): void;
}
