<?php

declare(strict_types=1);

namespace Module\Client\Domain\Invoice;

use Module\Client\Domain\Invoice\Event\InvoiceStatusChanged;
use Module\Client\Domain\Invoice\ValueObject\InvoiceId;
use Module\Client\Domain\Invoice\ValueObject\OrderIdCollection;
use Module\Client\Domain\Invoice\ValueObject\InvoiceStatusEnum;
use Module\Client\Domain\Shared\ValueObject\ClientId;
use Module\Shared\ValueObject\File;
use Module\Shared\ValueObject\Timestamps;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

final class Invoice extends AbstractAggregateRoot
{
    public function __construct(
        private readonly InvoiceId $id,
        private readonly ClientId $clientId,
        private InvoiceStatusEnum $status,
        private readonly OrderIdCollection $orders,
        private readonly File $document,
//        private readonly Timestamps $timestamps
    )
    {
    }

    public function id(): InvoiceId
    {
        return $this->id;
    }

    public function clientId(): ClientId
    {
        return $this->clientId;
    }

    public function status(): InvoiceStatusEnum
    {
        return $this->status;
    }

    public function orders(): OrderIdCollection
    {
        return $this->orders;
    }

    public function document(): File
    {
        return $this->document;
    }

//    public function timestamps(): Timestamps
//    {
//        return $this->timestamps;
//    }

    public function delete(): void
    {
        $this->updateStatus(InvoiceStatusEnum::DELETED);
    }

    private function updateStatus(InvoiceStatusEnum $status): void
    {
        if ($status === $this->status) {
            return;
        }

//        $beforeStatus = $this->status;
        $this->status = $status;
//        $this->pushEvent(new InvoiceStatusChanged($this, $beforeStatus));
    }
}