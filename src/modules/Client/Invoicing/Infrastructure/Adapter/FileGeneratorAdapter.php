<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Infrastructure\Adapter;

use Module\Booking\Invoicing\Application\RequestDto\GenerateInvoiceFileRequestDto;
use Module\Booking\Invoicing\Application\UseCase\GenerateInvoiceFile;
use Module\Client\Invoicing\Domain\Invoice\Adapter\FileGeneratorAdapterInterface;
use Module\Client\Invoicing\Domain\Order\ValueObject\OrderId;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Sdk\Shared\Dto\FileDto;

class FileGeneratorAdapter implements FileGeneratorAdapterInterface
{
    public function generate(string $filename, OrderId $orderId, ClientId $clientId): FileDto
    {
        return app(GenerateInvoiceFile::class)->execute(
            new GenerateInvoiceFileRequestDto(
                $filename,
                $orderId->value(),
                $clientId->value(),
            )
        );
    }
}
