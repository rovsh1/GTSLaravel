<?php

namespace Module\Client\Domain\Invoice\Repository;

use Module\Client\Domain\Invoice\Invoice;
use Module\Client\Domain\Invoice\ValueObject\InvoiceId;
use Module\Client\Domain\Invoice\ValueObject\OrderIdCollection;
use Module\Client\Domain\Order\ValueObject\OrderId;
use Module\Client\Domain\Shared\ValueObject\ClientId;
use Module\Shared\ValueObject\File;

interface InvoiceRepositoryInterface
{
    public function create(ClientId $clientId, OrderIdCollection $orders, File $document): Invoice;

    public function find(InvoiceId $id): ?Invoice;

    public function findByOrderId(OrderId $orderId): ?Invoice;

    public function store(Invoice $invoice): void;
}