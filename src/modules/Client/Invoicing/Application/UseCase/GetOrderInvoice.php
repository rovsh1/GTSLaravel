<?php

namespace Module\Client\Invoicing\Application\UseCase;

use Module\Client\Invoicing\Application\Dto\InvoiceDto;
use Module\Client\Invoicing\Domain\Invoice\Repository\InvoiceRepositoryInterface;
use Module\Client\Shared\Domain\ValueObject\OrderId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetOrderInvoice implements UseCaseInterface
{
    public function __construct(
        private readonly InvoiceRepositoryInterface $invoiceRepository,
    ) {}

    public function execute(int $orderId): ?InvoiceDto
    {
        $invoice = $this->invoiceRepository->findByOrderId(new OrderId($orderId));
        if ($invoice === null) {
            return null;
        }

        return new InvoiceDto(
            $invoice->id()->value(),
            $invoice->orderId()->value(),
            $invoice->document()->guid(),
            $invoice->timestamps()->createdAt()->format(DATE_ATOM),
            $invoice->sendAt()?->format(DATE_ATOM),
        );
    }
}
