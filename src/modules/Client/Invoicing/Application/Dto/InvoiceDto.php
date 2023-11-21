<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Application\Dto;

final class InvoiceDto
{
    public function __construct(
        public readonly int $id,
        public readonly int $orderId,
        public readonly string $document,
        public readonly string $createdAt,
    ) {}
}
