<?php

namespace Module\Invoicing\Domain\Invoice;

use Module\Invoicing\Domain\Invoice\ValueObject\InvoiceId;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

class Invoice extends AbstractAggregateRoot
{
    public function __construct(
        private readonly InvoiceId $id,
        private readonly array $bookings,
        private readonly \DateTimeInterface $createdAt,
    ) {
    }

    public function id(): InvoiceId
    {
        return $this->id;
    }
}