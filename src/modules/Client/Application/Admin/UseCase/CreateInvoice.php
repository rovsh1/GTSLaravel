<?php

declare(strict_types=1);

namespace Module\Client\Application\Admin\UseCase;

use Module\Client\Application\Admin\Dto\InvoiceDto;
use Module\Client\Application\Admin\Request\CreateInvoiceRequestDto;
use Module\Client\Domain\Invoice\Invoice;
use Module\Client\Domain\Invoice\Repository\InvoiceRepositoryInterface;
use Module\Client\Domain\Invoice\ValueObject\OrderIdCollection;
use Module\Client\Domain\Order\Order;
use Module\Client\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Client\Domain\Shared\ValueObject\ClientId;
use Module\Shared\Dto\StatusDto;
use Module\Shared\ValueObject\File;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class CreateInvoice implements UseCaseInterface
{
    public function __construct(
        private readonly InvoiceRepositoryInterface $invoiceRepository,
        private readonly OrderRepositoryInterface $orderRepository,
    ) {
    }

    public function execute(CreateInvoiceRequestDto $requestDto): InvoiceDto
    {
        $orderIdCollection = new OrderIdCollection($requestDto->orderIds);

        $orders = $this->prepareOrders($requestDto->clientId, $orderIdCollection);

        $invoice = $this->invoiceRepository->create(
            clientId: new ClientId($requestDto->clientId),
            orders: $orderIdCollection,
            document: new File($requestDto->file->guid)
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
            $order = $this->orderRepository->find($orderId->value());

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
