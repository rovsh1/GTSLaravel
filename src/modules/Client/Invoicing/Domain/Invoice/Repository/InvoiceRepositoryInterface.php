<?php

namespace Module\Client\Invoicing\Domain\Invoice\Repository;

use Module\Client\Invoicing\Domain\Invoice\Invoice;
use Module\Client\Invoicing\Domain\Invoice\ValueObject\InvoiceId;
use Module\Client\Invoicing\Domain\Invoice\ValueObject\OrderIdCollection;
use Module\Client\Invoicing\Domain\Order\ValueObject\OrderId;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Module\Shared\ValueObject\File;

interface InvoiceRepositoryInterface
{
    public function create(ClientId $clientId, OrderIdCollection $orders, ?File $document): Invoice;

    public function find(InvoiceId $id): ?Invoice;

    public function findByOrderId(OrderId $orderId): ?Invoice;

    public function store(Invoice $invoice): void;
}
