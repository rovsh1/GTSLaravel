<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Infrastructure\Adapter;

use Module\Booking\Invoicing\Application\RequestDto\GenerateInvoiceFileRequestDto;
use Module\Booking\Invoicing\Application\UseCase\GenerateInvoiceFile;
use Module\Client\Invoicing\Domain\Invoice\Adapter\FileGeneratorAdapterInterface;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Module\Client\Shared\Domain\ValueObject\OrderId;
use Sdk\Shared\Dto\FileDto;

class FileGeneratorAdapter implements FileGeneratorAdapterInterface
{
    public function generate(string $filename, OrderId $orderId): FileDto
    {
        return app(GenerateInvoiceFile::class)->execute(
            new GenerateInvoiceFileRequestDto(
                $filename,
                $orderId->value(),
            )
        );
    }
}
