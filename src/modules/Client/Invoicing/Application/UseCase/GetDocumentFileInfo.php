<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Application\UseCase;

use Module\Client\Invoicing\Domain\Invoice\Repository\InvoiceRepositoryInterface;
use Module\Client\Invoicing\Domain\Invoice\ValueObject\InvoiceId;
use Module\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Module\Shared\Exception\ApplicationException;
use Module\Support\FileStorage\Application\Dto\FileInfoDto;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetDocumentFileInfo implements UseCaseInterface
{
    public function __construct(
        private readonly InvoiceRepositoryInterface $invoiceRepository,
        private readonly FileStorageAdapterInterface $fileStorageAdapter
    ) {}

    public function execute(int $invoiceId): ?FileInfoDto
    {
        $invoice = $this->invoiceRepository->find(new InvoiceId($invoiceId));
        if ($invoice?->document()?->guid() === null) {
            throw new ApplicationException('Invoice or file not found',999);
        }

        return $this->fileStorageAdapter->getInfo($invoice->document()->guid());
    }
}
