<?php

namespace Module\Booking\Domain\Invoice\Repository;

use Module\Booking\Domain\Invoice\Invoice;
use Module\Booking\Domain\Invoice\ValueObject\InvoiceId;
use Module\Booking\Domain\Invoice\ValueObject\OrderIdCollection;
use Module\Shared\ValueObject\File;

interface InvoiceRepositoryInterface
{
    public function create(OrderIdCollection $orders, File $document): Invoice;

    public function find(InvoiceId $id): ?Invoice;

    public function store(Invoice $invoice): void;
}