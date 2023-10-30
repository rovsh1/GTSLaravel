<?php

declare(strict_types=1);

namespace Module\Client\Application\Request;

use Module\Shared\Dto\FileDto;

final class CreateInvoiceRequestDto
{
    public function __construct(
        public readonly int $clientId,
        public readonly array $orderIds,
        public readonly FileDto $file,
    ) {
    }
}
