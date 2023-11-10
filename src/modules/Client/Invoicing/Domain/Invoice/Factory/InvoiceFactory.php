<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Domain\Invoice\Factory;

use Module\Client\Invoicing\Domain\Invoice\Invoice;
use Module\Client\Invoicing\Domain\Invoice\Repository\InvoiceRepositoryInterface;
use Module\Client\Invoicing\Domain\Invoice\Service\TemplateDataFactory;
use Module\Client\Invoicing\Domain\Invoice\ValueObject\OrderIdCollection;
use Module\Client\Invoicing\Domain\Order\Order;
use Module\Client\Invoicing\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Module\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Module\Shared\Service\TemplateCompilerInterface;
use Module\Shared\ValueObject\File;
use Sdk\Module\Contracts\Event\DomainEventDispatcherInterface;

class InvoiceFactory
{
    public function __construct(
        private readonly TemplateDataFactory $templateDataFactory,
        private readonly FileStorageAdapterInterface $fileStorageAdapter,
        private readonly InvoiceRepositoryInterface $invoiceRepository,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly TemplateCompilerInterface $templateCompiler,
        private readonly DomainEventDispatcherInterface $eventDispatcher,
    ) {}

    public function generate(ClientId $clientId, OrderIdCollection $orderIds): Invoice
    {
        $orders = $this->prepareOrders($clientId, $orderIds);

        $fileDto = $this->fileStorageAdapter->create(
            'invoice_123.pdf',
            $this->generateContent($clientId, $orderIds)
        );

        $invoice = $this->invoiceRepository->create(
            clientId: $clientId,
            orders: $orderIds,
            document: new File($fileDto->guid)
        );

        $this->updateOrdersStatus($orders, $invoice);

        return $invoice;
    }

    private function getFilename(Invoice $invoice): string
    {
        return $invoice->id() . '-' . date('Ymd') . '.pdf';
    }

    private function generateContent(ClientId $clientId, OrderIdCollection $orderIds): string
    {
        $orderId = null;
        foreach ($orderIds as $oId) {
            $orderId = $oId;
            break;
        }
        $templateData = $this->templateDataFactory->build($clientId, $orderId);

        return $this->templateCompiler->compile('invoice.invoice', $templateData);
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
