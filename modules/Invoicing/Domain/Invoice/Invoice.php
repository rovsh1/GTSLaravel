<?php

namespace Module\Invoicing\Domain\Invoice;

use Module\Invoicing\Domain\Invoice\ValueObject\BookingIdCollection;
use Module\Invoicing\Domain\Invoice\ValueObject\InvoiceId;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

class Invoice extends AbstractAggregateRoot
{
    public function __construct(
        private readonly InvoiceId $id,
        private readonly BookingIdCollection $bookings,
        private readonly \DateTimeImmutable $createdAt,
    ) {
    }

    public function id(): InvoiceId
    {
        return $this->id;
    }

    public function bookings(): BookingIdCollection
    {
        return $this->bookings;
    }
}