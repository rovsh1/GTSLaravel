<?php

declare(strict_types=1);

namespace Module\Client\Domain\Order;

use Module\Client\Domain\Invoice\ValueObject\InvoiceId;
use Module\Client\Domain\Order\ValueObject\OrderId;
use Module\Client\Domain\Shared\ValueObject\ClientId;
use Module\Shared\Enum\Booking\OrderStatusEnum;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

final class Order extends AbstractAggregateRoot
{
    public function __construct(
        private readonly OrderId $id,
        private readonly ClientId $clientId,
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
