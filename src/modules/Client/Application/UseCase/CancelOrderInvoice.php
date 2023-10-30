<?php

namespace Module\Client\Application\UseCase;

use Module\Client\Domain\Invoice\Repository\InvoiceRepositoryInterface;
use Module\Client\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Client\Domain\Order\ValueObject\OrderId;
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