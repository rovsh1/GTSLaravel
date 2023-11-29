<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Application\UseCase;

use Module\Client\Invoicing\Application\Exception\InvoiceNotFoundException;
use Module\Client\Invoicing\Domain\Invoice\Repository\InvoiceRepositoryInterface;
use Module\Client\Shared\Domain\ValueObject\OrderId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SendInvoiceToClient implements UseCaseInterface
{
    public function __construct(
        private readonly InvoiceRepositoryInterface $invoiceRepository
    ) {}

    public function execute(int $orderId): void
    {
        $invoice = $this->invoiceRepository->findByOrderId(new OrderId($orderId));
        if ($invoice === null) {
            throw new InvoiceNotFoundException();
        }
    }
}
