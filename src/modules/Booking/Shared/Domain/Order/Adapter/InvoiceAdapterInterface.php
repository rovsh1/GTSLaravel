<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Order\Adapter;

use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;

interface InvoiceAdapterInterface
{
    public function cancelInvoice(OrderId $orderId): void;
}
