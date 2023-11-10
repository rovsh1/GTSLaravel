<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Domain\Invoice\Service\Dto;

class InvoiceDto
{
    public function __construct(
        public readonly string $number,
        public readonly string $createdAt,
    ) {}
}
