<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Infrastructure\Adapter;

use Module\Booking\Invoicing\Application\UseCase\GenerateInvoiceFile;
use Module\Client\Invoicing\Domain\Invoice\Adapter\FileGeneratorAdapterInterface;
use Module\Client\Invoicing\Domain\Invoice\ValueObject\InvoiceId;
use Module\Shared\Dto\FileDto;

class FileGeneratorAdapter implements FileGeneratorAdapterInterface
{
    public function generate(string $filename, InvoiceId $invoiceId): FileDto
    {
        return app(GenerateInvoiceFile::class)->execute($filename, $invoiceId->value());
    }
}
