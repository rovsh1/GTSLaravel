<?php

namespace Module\Client\Invoicing\Application\UseCase;

use Module\Client\Invoicing\Domain\Invoice\Repository\InvoiceRepositoryInterface;
use Module\Client\Invoicing\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Client\Invoicing\Domain\Order\ValueObject\OrderId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class CancelOrderInvoice implements UseCaseInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly InvoiceRepositoryInterface $invoiceRepository,
    ) {
    }

    public function execute(int $orderId): void
    {
        $order = $this->orderRepository->findOrFail(new OrderId($orderId));

        $order->ensureInvoiceCanBeCancelled();

        $invoice = $this->invoiceRepository->find($order->invoiceId());
        $invoice->delete();
        $this->invoiceRepository->store($invoice);
    }
}
