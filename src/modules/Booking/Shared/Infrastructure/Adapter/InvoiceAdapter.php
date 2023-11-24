<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Adapter;

use Module\Booking\Shared\Domain\Order\Adapter\InvoiceAdapterInterface;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;
use Module\Client\Invoicing\Application\UseCase\CancelOrderInvoice;

class InvoiceAdapter implements InvoiceAdapterInterface
{
    public function cancelInvoice(OrderId $orderId): void
    {
        app(CancelOrderInvoice::class)->execute($orderId->value());
    }
}
