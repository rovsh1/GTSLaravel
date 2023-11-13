<?php

declare(strict_types=1);

namespace Module\Booking\Invoicing\Domain\Service;

use Module\Booking\Invoicing\Domain\ValueObject\InvoiceId;
use Module\Booking\Invoicing\Domain\ValueObject\OrderIdCollection;
use Module\Booking\Shared\Domain\Order\ValueObject\ClientId;
use Module\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Module\Shared\Dto\FileDto;
use Module\Shared\Service\TemplateCompilerInterface;

class FileGenerator
{
    public function __construct(
        private readonly FileStorageAdapterInterface $fileStorageAdapter,
        private readonly TemplateDataFactory $templateDataFactory,
        private readonly TemplateCompilerInterface $templateCompiler,
    ) {}

    public function generate(
        string $filename,
        InvoiceId $invoiceId,
        OrderIdCollection $orderIds,
        ClientId $clientId,
        \DateTimeInterface $createdAt
    ): FileDto {
        $templateData = $this->templateDataFactory->build($invoiceId, $orderIds, $clientId, $createdAt);

        $content = $this->templateCompiler->compile('invoice.invoice', $templateData);

        return $this->fileStorageAdapter->create($filename, $content);
    }
}
