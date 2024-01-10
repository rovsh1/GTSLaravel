<?php

declare(strict_types=1);

namespace Module\Booking\Invoicing\Infrastructure\Repository;

use Module\Booking\Invoicing\Domain\Repository\InvoiceRepositoryInterface;
use Module\Booking\Invoicing\Infrastructure\Models\Invoice;
use Sdk\Booking\ValueObject\OrderId;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    public function getInvoiceFileGuid(OrderId $orderId): ?string
    {
        $model = Invoice::whereOrderId($orderId->value())->first();
        if ($model === null) {
            return null;
        }

        return $model->document;
    }
}
