<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Domain\Order;

use Module\Client\Invoicing\Domain\Invoice\ValueObject\InvoiceId;
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
        private ?InvoiceId $invoiceId,
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

    public function invoiceId(): ?InvoiceId
    {
        return $this->invoiceId;
    }

    public function status(): OrderStatusEnum
    {
        return $this->status;
    }

    public function setInvoiceId(InvoiceId $invoiceId): void
    {
        $this->invoiceId = $invoiceId;
        $this->status = OrderStatusEnum::INVOICED;
    }

    public function ensureInvoiceCreationAvailable(): void
    {
        if ($this->status !== OrderStatusEnum::WAITING_INVOICE) {
            throw new \Exception('Order status invalid for create invoice');
        }

        if (!empty($this->invoiceId)) {
            throw new \Exception("Order [$this->id] already has invoice");
        }
    }
}
