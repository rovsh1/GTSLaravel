<?php

namespace Module\Booking\Domain\Invoice\Service;

use Module\Booking\Domain\Invoice\Invoice;
use Module\Booking\Domain\Invoice\ValueObject\ClientPaymentCollection;
use Module\Booking\Domain\Invoice\ValueObject\InvoiceAmountCollection;
use Module\Booking\Domain\Invoice\ValueObject\SupplierPaymentCollection;

class InvoicePaymentBuilder
{
    private Invoice $invoice;

    public function setInvoice(Invoice $invoice): void
    {
        $this->invoice = $invoice;
    }

    public function buildClientPayments(): ClientPaymentCollection
    {
        return new ClientPaymentCollection();
    }

    public function buildSupplierPayments(): SupplierPaymentCollection
    {
        return new SupplierPaymentCollection();
    }
}