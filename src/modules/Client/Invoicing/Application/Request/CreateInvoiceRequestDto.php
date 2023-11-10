<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Application\Request;

final class CreateInvoiceRequestDto
{
    public function __construct(
        public readonly int $clientId,
        public readonly array $orderIds,
    ) {
    }
}
