<?php

declare(strict_types=1);

namespace Module\Booking\Invoicing\Domain\Service;

use Module\Booking\Shared\Domain\Order\ValueObject\ClientId;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;
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

    public function generate(string $filename, OrderId $orderId, ClientId $clientId): FileDto
    {
        $templateData = $this->templateDataFactory->build($orderId, $clientId);

        $content = $this->templateCompiler->compile('invoice.invoice', $templateData);

        return $this->fileStorageAdapter->create($filename, $content);
    }
}
