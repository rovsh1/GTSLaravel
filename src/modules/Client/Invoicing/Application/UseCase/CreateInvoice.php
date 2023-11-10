<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Application\UseCase;

use Module\Client\Invoicing\Application\Dto\InvoiceDto;
use Module\Client\Invoicing\Application\Request\CreateInvoiceRequestDto;
use Module\Client\Invoicing\Domain\Invoice\Invoice;
use Module\Client\Invoicing\Domain\Invoice\Repository\InvoiceRepositoryInterface;
use Module\Client\Invoicing\Domain\Invoice\ValueObject\OrderIdCollection;
use Module\Client\Invoicing\Domain\Order\Order;
use Module\Client\Invoicing\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Client\Invoicing\Domain\Order\ValueObject\OrderId;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Module\Shared\Dto\StatusDto;
use Module\Shared\ValueObject\File;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class CreateInvoice implements UseCaseInterface
{
    public function __construct(
        private readonly InvoiceRepositoryInterface $invoiceRepository,
        private readonly OrderRepositoryInterface $orderRepository,
    ) {}

    public function execute(CreateInvoiceRequestDto $requestDto): InvoiceDto
    {
        $orderIdCollection = new OrderIdCollection(
            array_map(fn(int $orderId) => new OrderId($orderId), $requestDto->orderIds)
        );

        $orders = $this->prepareOrders($requestDto->clientId, $orderIdCollection);

        $invoice = $this->invoiceRepository->create(
            clientId: new ClientId($requestDto->clientId),
            orders: $orderIdCollection,
            document: new File('test')//@todo generate invoice document
        );

        $this->updateOrdersStatus($orders, $invoice);

        return new InvoiceDto(
            id: $invoice->id()->value(),
            status: StatusDto::createFromEnum($invoice->status())
        );
    }

    private function prepareOrders(int $clientId, OrderIdCollection $orderIdCollection): array
    {
        $orders = [];
        foreach ($orderIdCollection as $orderId) {
            $order = $this->orderRepository->find($orderId);

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
