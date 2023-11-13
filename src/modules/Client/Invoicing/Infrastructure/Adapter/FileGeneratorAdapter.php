<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Infrastructure\Adapter;

use Module\Booking\Invoicing\Application\Request\GenerateInvoiceFileRequestDto;
use Module\Booking\Invoicing\Application\UseCase\GenerateInvoiceFile;
use Module\Client\Invoicing\Domain\Invoice\Adapter\FileGeneratorAdapterInterface;
use Module\Client\Invoicing\Domain\Invoice\ValueObject\InvoiceId;
use Module\Client\Invoicing\Domain\Invoice\ValueObject\OrderIdCollection;
use Module\Client\Invoicing\Domain\Order\ValueObject\OrderId;
use Module\Client\Shared\Domain\ValueObject\ClientId;
use Module\Shared\Dto\FileDto;

class FileGeneratorAdapter implements FileGeneratorAdapterInterface
{
    public function generate(
        string $filename,
        InvoiceId $invoiceId,
        OrderIdCollection $orderIds,
        ClientId $clientId,
        \DateTimeInterface $createdAt
    ): FileDto {
        return app(GenerateInvoiceFile::class)->execute(
            new GenerateInvoiceFileRequestDto(
                $filename,
                $invoiceId->value(),
                $orderIds->map(fn(OrderId $orderId) => $orderId->value()),
                $clientId->value(),
                $createdAt
            )
        );
    }
}
