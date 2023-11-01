<?php

declare(strict_types=1);

namespace Module\Client\Application\Admin\Dto;

use Module\Shared\Dto\StatusDto;

final class InvoiceDto
{
    public function __construct(
        public readonly int $id,
        public readonly StatusDto $status,
    ) {
    }
}
