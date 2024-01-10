<?php

declare(strict_types=1);

namespace Module\Booking\Invoicing\Domain\Repository;


use Sdk\Booking\ValueObject\OrderId;

interface InvoiceRepositoryInterface
{
    public function getInvoiceFileGuid(OrderId $orderId): ?string;
}
