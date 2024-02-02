<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Domain\Invoice\Factory;

use Illuminate\Support\Facades\DB;
use Module\Client\Invoicing\Application\Exception\InvoiceCreatingForbiddenException;
use Module\Client\Invoicing\Domain\Invoice\Adapter\FileGeneratorAdapterInterface;
use Module\Client\Invoicing\Domain\Invoice\Exception\InvalidOrderStatusToCreateInvoice;
use Module\Client\Invoicing\Domain\Invoice\Exception\OrderAlreadyHasInvoice;
use Module\Client\Invoicing\Domain\Invoice\Invoice;
use Module\Client\Invoicing\Domain\Invoice\Repository\InvoiceRepositoryInterface;
use Module\Client\Invoicing\Domain\Order\Order;
use Module\Client\Invoicing\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Client\Shared\Domain\ValueObject\OrderId;
use Sdk\Shared\Enum\Order\OrderStatusEnum;
use Sdk\Shared\ValueObject\File;

class InvoiceFactory
{
    public function __construct(
        private readonly FileGeneratorAdapterInterface $fileGeneratorAdapter,
        private readonly InvoiceRepositoryInterface $invoiceRepository,
        private readonly OrderRepositoryInterface $orderRepository,
    ) {}

    public function generate(OrderId $orderId): Invoice
    {
        $order = $this->prepareOrder($orderId);

        $invoice = DB::transaction(function () use ($order) {
            $fileDto = $this->fileGeneratorAdapter->generate(
                $this->getFilename($order->id()),
                $order->id(),
            );

            $invoice = $this->invoiceRepository->create(
                clientId: $order->clientId(),
                orderId: $order->id(),
                document: new File($fileDto->guid)
            );

            $this->invoiceRepository->store($invoice);

            return $invoice;
        });

        $this->updateOrderStatus($order);

        return $invoice;
    }

    private function getFilename(OrderId $orderId): string
    {
        return $orderId->value() . '-' . date('Ymd') . '.pdf';
    }

    private function prepareOrder(OrderId $orderId): Order
    {
        $existOrderInvoice = $this->invoiceRepository->findByOrderId($orderId);
        if ($existOrderInvoice !== null) {
            throw new OrderAlreadyHasInvoice();
        }

        $order = $this->orderRepository->findOrFail($orderId);

        try {
            $order->ensureInvoiceCreationAvailable();
        } catch (InvalidOrderStatusToCreateInvoice $e) {
            throw new InvoiceCreatingForbiddenException($e);
        }

        return $order;
    }

    private function updateOrderStatus(Order $order): void
    {
        if ($order->status() === OrderStatusEnum::REFUND_FEE) {
            return;
        }
        $order->invoiced();
        $this->orderRepository->store($order);
    }
}
