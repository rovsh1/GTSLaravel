<?php

declare(strict_types=1);

namespace Module\Client\Domain\Payment\Entity;

use Module\Client\Domain\Invoice\ValueObject\InvoiceId;
use Module\Client\Domain\Order\ValueObject\OrderId;
use Module\Client\Domain\Payment\ValueObject\PaymentId;
use Module\Client\Domain\Payment\ValueObject\PlantId;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

final class Plant extends AbstractAggregateRoot
{
    public function __construct(
        private readonly PlantId $id,
        private readonly PaymentId $paymentId,
        private readonly InvoiceId $invoiceId,
        private readonly OrderId $orderId,
        private readonly float $sum,
    ) {
    }

    public function id(): PlantId
    {
        return $this->id;
    }

    public function paymentId(): PaymentId
    {
        return $this->paymentId;
    }

    public function invoiceId(): InvoiceId
    {
        return $this->invoiceId;
    }

    public function orderId(): OrderId
    {
        return $this->orderId;
    }

    public function sum(): float
    {
        return $this->sum;
    }
}