<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Adapter;

use Module\Booking\Shared\Domain\Order\Adapter\InvoiceAdapterInterface;
use Module\Client\Invoicing\Application\UseCase\CancelOrderInvoice;
use Sdk\Booking\ValueObject\OrderId;

class InvoiceAdapter implements InvoiceAdapterInterface
{
    public function cancelInvoice(OrderId $orderId): void
    {
        app(CancelOrderInvoice::class)->execute($orderId->value());
    }
}
