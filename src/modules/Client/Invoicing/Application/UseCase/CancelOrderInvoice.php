<?php

namespace Module\Client\Invoicing\Application\UseCase;

use Module\Client\Invoicing\Application\Exception\CancellationForbiddenException;
use Module\Client\Invoicing\Application\Exception\InvoiceNotFoundException;
use Module\Client\Invoicing\Domain\Invoice\Exception\InvalidOrderStatusToCancelInvoice;
use Module\Client\Invoicing\Domain\Invoice\Repository\InvoiceRepositoryInterface;
use Module\Client\Invoicing\Domain\Order\Order;
use Module\Client\Invoicing\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Client\Invoicing\Domain\Order\ValueObject\OrderId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class CancelOrderInvoice implements UseCaseInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly InvoiceRepositoryInterface $invoiceRepository,
    ) {}

    public function execute(int $orderId): void
    {
        $order = $this->orderRepository->findOrFail(new OrderId($orderId));

        try {
            $order->ensureInvoiceCanBeCancelled();
        } catch (InvalidOrderStatusToCancelInvoice $e) {
            throw new CancellationForbiddenException($e);
        }

        $invoice = $this->invoiceRepository->findByOrderId($order->id());
        if ($invoice === null) {
            throw new InvoiceNotFoundException();
        }
        $invoice->delete();
        $this->invoiceRepository->store($invoice);

        $this->updateOrderStatus($order);
    }

    public function updateOrderStatus(Order $order): void
    {
        $order->toInProgress();
        $this->orderRepository->store($order);
    }
}
