<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Domain\Invoice;

use Module\Client\Invoicing\Domain\Invoice\ValueObject\InvoiceId;
use Module\Client\Invoicing\Domain\Order\ValueObject\OrderId;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Module\Shared\ValueObject\File;
use Module\Shared\ValueObject\Timestamps;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

final class Invoice extends AbstractAggregateRoot
{
    private bool $isDeleted = false;

    public function __construct(
        private readonly InvoiceId $id,
        private readonly ClientId $clientId,
        private readonly OrderId $orderId,
        private readonly File $document,
        private readonly Timestamps $timestamps
        //@todo добавить стоимость инвоисов
    ) {}

    public function id(): InvoiceId
    {
        return $this->id;
    }

    public function clientId(): ClientId
    {
        return $this->clientId;
    }

    public function orderId(): OrderId
    {
        return $this->orderId;
    }

    public function document(): File
    {
        return $this->document;
    }

    public function timestamps(): Timestamps
    {
        return $this->timestamps;
    }

    public function delete(): void
    {
        $this->isDeleted = true;
    }

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }
}
