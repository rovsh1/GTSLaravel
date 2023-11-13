<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Application\UseCase;

use Module\Client\Invoicing\Application\Dto\InvoiceDto;
use Module\Client\Invoicing\Application\Request\CreateInvoiceRequestDto;
use Module\Client\Invoicing\Domain\Invoice\Factory\InvoiceFactory;
use Module\Client\Invoicing\Domain\Invoice\ValueObject\OrderIdCollection;
use Module\Client\Invoicing\Domain\Order\ValueObject\OrderId;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Module\Shared\Dto\StatusDto;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class CreateInvoice implements UseCaseInterface
{
    public function __construct(
        private readonly InvoiceFactory $invoiceFactory,
    ) {}

    public function execute(CreateInvoiceRequestDto $request): InvoiceDto
    {
        $orderIds = new OrderIdCollection(
            array_map(fn(int $orderId) => new OrderId($orderId), $request->orderIds)
        );

        $invoice = $this->invoiceFactory->generate(new ClientId($request->clientId), $orderIds);

        return new InvoiceDto(
            id: $invoice->id()->value(),
            status: StatusDto::createFromEnum($invoice->status())
        );
    }
}
