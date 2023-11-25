<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Domain\Invoice\Factory;

use Module\Client\Invoicing\Application\Exception\InvoiceCreatingForbiddenException;
use Module\Client\Invoicing\Domain\Invoice\Adapter\FileGeneratorAdapterInterface;
use Module\Client\Invoicing\Domain\Invoice\Exception\InvalidOrderStatusToCreateInvoice;
use Module\Client\Invoicing\Domain\Invoice\Exception\OrderAlreadyHasInvoice;
use Module\Client\Invoicing\Domain\Invoice\Invoice;
use Module\Client\Invoicing\Domain\Invoice\Repository\InvoiceRepositoryInterface;
use Module\Client\Invoicing\Domain\Order\Order;
use Module\Client\Invoicing\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Client\Invoicing\Domain\Order\ValueObject\OrderId;
use Module\Shared\Contracts\Service\SafeExecutorInterface;
use Module\Shared\ValueObject\File;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;

class InvoiceFactory
{
    public function __construct(
        private readonly FileGeneratorAdapterInterface $fileGeneratorAdapter,
        private readonly InvoiceRepositoryInterface $invoiceRepository,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly SafeExecutorInterface $executor,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function generate(OrderId $orderId): Invoice
    {
        $order = $this->prepareOrder($orderId);

        $handler = function () use ($order) {
            $fileDto = $this->fileGeneratorAdapter->generate(
                $this->getFilename($order->id()),
                $order->id(),
                $order->clientId()
            );

            $invoice = $this->invoiceRepository->create(
                clientId: $order->clientId(),
                orderId: $order->id(),
                document: new File($fileDto->guid)
            );

            $this->invoiceRepository->store($invoice);

            return $invoice;
        };

        $invoice = $this->executor->execute($handler);

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
        $order->invoiced();
        $this->orderRepository->store($order);
    }
}
