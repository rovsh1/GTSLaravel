<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Domain\Invoice\Factory;

use Module\Client\Invoicing\Domain\Invoice\Adapter\FileGeneratorAdapterInterface;
use Module\Client\Invoicing\Domain\Invoice\Invoice;
use Module\Client\Invoicing\Domain\Invoice\Repository\InvoiceRepositoryInterface;
use Module\Client\Invoicing\Domain\Invoice\ValueObject\OrderIdCollection;
use Module\Client\Invoicing\Domain\Order\Order;
use Module\Client\Invoicing\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Module\Shared\ValueObject\File;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;

class InvoiceFactory
{
    public function __construct(
        private readonly FileGeneratorAdapterInterface $fileGeneratorAdapter,
        private readonly InvoiceRepositoryInterface $invoiceRepository,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function generate(ClientId $clientId, OrderIdCollection $orderIds): Invoice
    {
        $orders = $this->prepareOrders($clientId, $orderIds);

        $invoice = $this->invoiceRepository->create(
            clientId: $clientId,
            orders: $orderIds,
            document: null
        );

        $fileDto = $this->fileGeneratorAdapter->generate(
            $this->getFilename($invoice),
            $invoice->id(),
            $invoice->orders(),
            $invoice->clientId(),
        );
        $invoice->setDocument(new File($fileDto->guid));

        $this->invoiceRepository->store($invoice);

        $this->updateOrdersStatus($orders, $invoice);

        return $invoice;
    }

    private function getFilename(Invoice $invoice): string
    {
        return $invoice->id() . '-' . date('Ymd') . '.pdf';
    }

    private function prepareOrders(ClientId $clientId, OrderIdCollection $orderIdCollection): array
    {
        $orders = [];
        foreach ($orderIdCollection as $orderId) {
            $order = $this->orderRepository->findOrFail($orderId);

            if (!$order->clientId()->isEqual($clientId)) {
                throw new \Exception('Cant invoice order with different client');
            }

            $order->ensureInvoiceCreationAvailable();

            $orders[] = $order;
        }

        return $orders;
    }

    private function updateOrdersStatus(array $orders, Invoice $invoice): void
    {
        /** @var Order $order */
        foreach ($orders as $order) {
            $order->setInvoiceId($invoice->id());

            $this->orderRepository->store($order);
        }
    }
}
