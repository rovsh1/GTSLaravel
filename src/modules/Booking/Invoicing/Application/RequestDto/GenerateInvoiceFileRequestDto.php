<?php

declare(strict_types=1);

namespace Module\Booking\Invoicing\Application\RequestDto;

class GenerateInvoiceFileRequestDto
{
    public function __construct(
        public readonly string $filename,
        public readonly int $invoiceId,
        public readonly array $orderIds,
        public readonly int $clientId,
        public readonly \DateTimeInterface $createdAt
    ) {}
}
