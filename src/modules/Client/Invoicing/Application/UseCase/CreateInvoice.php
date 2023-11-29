<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Application\UseCase;

use Module\Client\Invoicing\Application\Dto\InvoiceDto;
use Module\Client\Invoicing\Domain\Invoice\Factory\InvoiceFactory;
use Module\Client\Shared\Domain\ValueObject\OrderId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class CreateInvoice implements UseCaseInterface
{
    public function __construct(
        private readonly InvoiceFactory $invoiceFactory,
    ) {}

    public function execute(int $orderId): InvoiceDto
    {
        $invoice = $this->invoiceFactory->generate(new OrderId($orderId));

        return new InvoiceDto(
            id: $invoice->id()->value(),
            orderId: $orderId,
            document: $invoice->document()->guid(),
            createdAt: $invoice->timestamps()->createdAt()->format(DATE_ATOM)
        );
    }
}
