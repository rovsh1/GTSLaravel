<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Order\Service\VoucherFileGenerator;

use Module\Booking\Shared\Domain\Shared\Service\TemplateCompilerInterface;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Shared\Contracts\Adapter\FileStorageAdapterInterface;
use Sdk\Shared\Dto\FileDto;

class FileGenerator
{
    public function __construct(
        private readonly FileStorageAdapterInterface $fileStorageAdapter,
        private readonly TemplateDataFactory $templateDataFactory,
        private readonly TemplateCompilerInterface $templateCompiler,
    ) {}

    public function generate(string $filename, OrderId $orderId): FileDto
    {
        $templateData = $this->templateDataFactory->build($orderId);

        $content = $this->templateCompiler->compile('voucher.voucher', $templateData);

        return $this->fileStorageAdapter->create($filename, $content);
    }
}
