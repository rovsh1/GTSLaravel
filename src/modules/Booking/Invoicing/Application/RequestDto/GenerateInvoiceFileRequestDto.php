<?php

declare(strict_types=1);

namespace Module\Booking\Invoicing\Application\RequestDto;

class GenerateInvoiceFileRequestDto
{
    public function __construct(
        public readonly string $filename,
        public readonly int $orderId,
        public readonly int $clientId,
    ) {}
}
