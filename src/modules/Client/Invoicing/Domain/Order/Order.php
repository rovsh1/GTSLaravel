<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Domain\Order;

use Module\Client\Invoicing\Domain\Invoice\Exception\InvalidOrderStatusToCancelInvoice;
use Module\Client\Invoicing\Domain\Invoice\Exception\InvalidOrderStatusToCreateInvoice;
use Module\Client\Invoicing\Domain\Order\ValueObject\OrderId;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Module\Shared\Enum\CurrencyEnum;
use Module\Shared\Enum\Order\OrderStatusEnum;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

final class Order extends AbstractAggregateRoot
{
    public function __construct(
        private readonly OrderId $id,
        private readonly ClientId $clientId,
        private readonly CurrencyEnum $currency,
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

    public function currency(): CurrencyEnum
    {
        return $this->currency;
    }

    public function status(): OrderStatusEnum
    {
        return $this->status;
    }

    public function invoiced(): void
    {
        $this->status = OrderStatusEnum::INVOICED;
    }

    public function toInProgress(): void
    {
        $this->status = OrderStatusEnum::IN_PROGRESS;
    }

    public function ensureInvoiceCreationAvailable(): void
    {
        if ($this->status !== OrderStatusEnum::WAITING_INVOICE) {
            throw new InvalidOrderStatusToCreateInvoice();
        }
    }

    public function ensureInvoiceCanBeCancelled(): void
    {
        if ($this->status !== OrderStatusEnum::INVOICED) {
            throw new InvalidOrderStatusToCancelInvoice();
        }
    }
}
