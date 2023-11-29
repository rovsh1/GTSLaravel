<?php

declare(strict_types=1);

namespace Module\Client\Payment\Domain\Order;

use Module\Client\Shared\Domain\ValueObject\ClientId;
use Module\Client\Shared\Domain\ValueObject\OrderId;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;
use Sdk\Shared\Enum\Order\OrderStatusEnum;
use Sdk\Shared\ValueObject\Money;

final class Order extends AbstractAggregateRoot
{
    public function __construct(
        private readonly OrderId $id,
        private readonly ClientId $clientId,
        private readonly Money $clientPrice,
        private readonly Money $payedAmount,
        private OrderStatusEnum $status,
    ) {}

    public function id(): OrderId
    {
        return $this->id;
    }

    public function clientId(): ClientId
    {
        return $this->clientId;
    }

    public function status(): OrderStatusEnum
    {
        return $this->status;
    }

    public function clientPrice(): Money
    {
        return $this->clientPrice;
    }

    public function payedAmount(): Money
    {
        return $this->payedAmount;
    }

    public function invoiced(): void
    {
        $this->status = OrderStatusEnum::INVOICED;
    }

    public function paid(): void
    {
        $this->status = OrderStatusEnum::PAID;
    }

    public function partialPaid(): void
    {
        $this->status = OrderStatusEnum::PARTIAL_PAID;
    }
}
