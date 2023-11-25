<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Application\UseCase;

use Module\Client\Invoicing\Application\Exception\InvoiceNotFoundException;
use Module\Client\Invoicing\Domain\Invoice\Repository\InvoiceRepositoryInterface;
use Module\Client\Invoicing\Domain\Order\ValueObject\OrderId;
use Module\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Module\Support\FileStorage\Application\Dto\FileInfoDto;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetDocumentFileInfo implements UseCaseInterface
{
    public function __construct(
        private readonly InvoiceRepositoryInterface $invoiceRepository,
        private readonly FileStorageAdapterInterface $fileStorageAdapter
    ) {}

    public function execute(int $orderId): ?FileInfoDto
    {
        $invoice = $this->invoiceRepository->findByOrderId(new OrderId($orderId));
        if ($invoice?->document()?->guid() === null) {
            throw new InvoiceNotFoundException();
        }

        return $this->fileStorageAdapter->getInfo($invoice->document()->guid());
    }
}
